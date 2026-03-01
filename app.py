from __future__ import annotations

import os
import sqlite3
from datetime import datetime
from functools import wraps
from pathlib import Path
from typing import Any

from flask import Flask, flash, g, redirect, render_template, request, session, url_for
from werkzeug.security import check_password_hash, generate_password_hash

BASE_DIR = Path(__file__).resolve().parent
DB_PATH = BASE_DIR / "store.db"

app = Flask(__name__)
app.config["SECRET_KEY"] = os.getenv("SECRET_KEY", "dev-secret-change-me")


def get_db() -> sqlite3.Connection:
    if "db" not in g:
        g.db = sqlite3.connect(DB_PATH)
        g.db.row_factory = sqlite3.Row
    return g.db


@app.teardown_appcontext
def close_db(_: Any) -> None:
    db = g.pop("db", None)
    if db is not None:
        db.close()


def init_db() -> None:
    db = get_db()
    db.executescript(
        """
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL,
            role TEXT NOT NULL CHECK(role IN ('admin', 'user')),
            created_at TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            price REAL NOT NULL,
            file_url TEXT NOT NULL,
            created_at TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            amount REAL NOT NULL,
            status TEXT NOT NULL CHECK(status IN ('pending', 'paid')),
            created_at TEXT NOT NULL,
            paid_at TEXT,
            FOREIGN KEY(user_id) REFERENCES users(id),
            FOREIGN KEY(product_id) REFERENCES products(id)
        );

        CREATE TABLE IF NOT EXISTS purchases (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            order_id INTEGER NOT NULL,
            created_at TEXT NOT NULL,
            UNIQUE(user_id, product_id),
            FOREIGN KEY(user_id) REFERENCES users(id),
            FOREIGN KEY(product_id) REFERENCES products(id),
            FOREIGN KEY(order_id) REFERENCES orders(id)
        );
        """
    )

    admin_exists = db.execute("SELECT id FROM users WHERE role='admin' LIMIT 1").fetchone()
    if not admin_exists:
        db.execute(
            """
            INSERT INTO users (name, email, password_hash, role, created_at)
            VALUES (?, ?, ?, 'admin', ?)
            """,
            (
                "Admin",
                "admin@example.com",
                generate_password_hash("admin123"),
                datetime.utcnow().isoformat(),
            ),
        )

    demo_products = db.execute("SELECT COUNT(*) as c FROM products").fetchone()["c"]
    if demo_products == 0:
        now = datetime.utcnow().isoformat()
        db.executemany(
            """
            INSERT INTO products (title, description, price, file_url, created_at)
            VALUES (?, ?, ?, ?, ?)
            """,
            [
                (
                    "Creator Marketing Kit",
                    "Canva templates, email swipes, and launch checklist.",
                    39.0,
                    "https://example.com/downloads/creator-kit.zip",
                    now,
                ),
                (
                    "Notion Business Dashboard",
                    "A complete Notion workspace for digital product sellers.",
                    59.0,
                    "https://example.com/downloads/notion-dashboard.zip",
                    now,
                ),
            ],
        )

    db.commit()


def login_required(view):
    @wraps(view)
    def wrapped_view(*args, **kwargs):
        if session.get("user_id") is None:
            flash("Please log in first.", "warning")
            return redirect(url_for("login"))
        return view(*args, **kwargs)

    return wrapped_view


def admin_required(view):
    @wraps(view)
    def wrapped_view(*args, **kwargs):
        if session.get("role") != "admin":
            flash("Admin access required.", "danger")
            return redirect(url_for("dashboard"))
        return view(*args, **kwargs)

    return wrapped_view


@app.route("/")
def index():
    db = get_db()
    products = db.execute("SELECT * FROM products ORDER BY created_at DESC").fetchall()
    return render_template("index.html", products=products)


@app.route("/register", methods=["GET", "POST"])
def register():
    if request.method == "POST":
        name = request.form["name"].strip()
        email = request.form["email"].strip().lower()
        password = request.form["password"]

        if not name or not email or not password:
            flash("All fields are required.", "danger")
            return redirect(url_for("register"))

        db = get_db()
        try:
            db.execute(
                """
                INSERT INTO users (name, email, password_hash, role, created_at)
                VALUES (?, ?, ?, 'user', ?)
                """,
                (name, email, generate_password_hash(password), datetime.utcnow().isoformat()),
            )
            db.commit()
        except sqlite3.IntegrityError:
            flash("Email already registered.", "danger")
            return redirect(url_for("register"))

        flash("Account created. Please log in.", "success")
        return redirect(url_for("login"))

    return render_template("register.html")


@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        email = request.form["email"].strip().lower()
        password = request.form["password"]

        db = get_db()
        user = db.execute("SELECT * FROM users WHERE email = ?", (email,)).fetchone()

        if user is None or not check_password_hash(user["password_hash"], password):
            flash("Invalid email or password.", "danger")
            return redirect(url_for("login"))

        session.clear()
        session["user_id"] = user["id"]
        session["role"] = user["role"]
        session["name"] = user["name"]
        flash("Logged in successfully.", "success")
        return redirect(url_for("dashboard"))

    return render_template("login.html")


@app.route("/logout")
def logout():
    session.clear()
    flash("You are logged out.", "info")
    return redirect(url_for("index"))


@app.route("/dashboard")
@login_required
def dashboard():
    db = get_db()
    user_id = session["user_id"]

    purchased_products = db.execute(
        """
        SELECT p.*
        FROM purchases pu
        JOIN products p ON p.id = pu.product_id
        WHERE pu.user_id = ?
        ORDER BY pu.created_at DESC
        """,
        (user_id,),
    ).fetchall()

    products = db.execute(
        """
        SELECT pr.*,
               EXISTS(
                   SELECT 1 FROM purchases pu WHERE pu.user_id = ? AND pu.product_id = pr.id
               ) AS owned
        FROM products pr
        ORDER BY pr.created_at DESC
        """,
        (user_id,),
    ).fetchall()

    orders = db.execute(
        """
        SELECT o.*, p.title AS product_title
        FROM orders o
        JOIN products p ON p.id = o.product_id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
        """,
        (user_id,),
    ).fetchall()

    return render_template(
        "dashboard.html",
        purchased_products=purchased_products,
        products=products,
        orders=orders,
    )


@app.route("/buy/<int:product_id>", methods=["POST"])
@login_required
def buy(product_id: int):
    db = get_db()
    user_id = session["user_id"]

    owned = db.execute(
        "SELECT 1 FROM purchases WHERE user_id = ? AND product_id = ?",
        (user_id, product_id),
    ).fetchone()
    if owned:
        flash("You already own this product.", "info")
        return redirect(url_for("dashboard"))

    product = db.execute("SELECT * FROM products WHERE id = ?", (product_id,)).fetchone()
    if not product:
        flash("Product not found.", "danger")
        return redirect(url_for("dashboard"))

    db.execute(
        """
        INSERT INTO orders (user_id, product_id, amount, status, created_at)
        VALUES (?, ?, ?, 'pending', ?)
        """,
        (user_id, product_id, product["price"], datetime.utcnow().isoformat()),
    )
    db.commit()

    flash("Order created. Complete payment to unlock the product.", "warning")
    return redirect(url_for("dashboard"))


@app.route("/pay/<int:order_id>", methods=["POST"])
@login_required
def pay(order_id: int):
    db = get_db()
    user_id = session["user_id"]

    order = db.execute(
        "SELECT * FROM orders WHERE id = ? AND user_id = ?", (order_id, user_id)
    ).fetchone()
    if not order:
        flash("Order not found.", "danger")
        return redirect(url_for("dashboard"))

    if order["status"] == "paid":
        flash("Order already paid.", "info")
        return redirect(url_for("dashboard"))

    paid_at = datetime.utcnow().isoformat()
    db.execute("UPDATE orders SET status='paid', paid_at=? WHERE id=?", (paid_at, order_id))
    db.execute(
        """
        INSERT OR IGNORE INTO purchases (user_id, product_id, order_id, created_at)
        VALUES (?, ?, ?, ?)
        """,
        (user_id, order["product_id"], order_id, paid_at),
    )
    db.commit()

    flash("Payment successful. Product unlocked in your library.", "success")
    return redirect(url_for("dashboard"))


@app.route("/admin")
@login_required
@admin_required
def admin_panel():
    db = get_db()
    products = db.execute("SELECT * FROM products ORDER BY created_at DESC").fetchall()
    orders = db.execute(
        """
        SELECT o.*, u.email, p.title AS product_title
        FROM orders o
        JOIN users u ON u.id = o.user_id
        JOIN products p ON p.id = o.product_id
        ORDER BY o.created_at DESC
        """
    ).fetchall()
    return render_template("admin.html", products=products, orders=orders)


@app.route("/admin/products", methods=["POST"])
@login_required
@admin_required
def create_product():
    title = request.form["title"].strip()
    description = request.form["description"].strip()
    file_url = request.form["file_url"].strip()

    try:
        price = float(request.form["price"])
    except ValueError:
        flash("Price must be a valid number.", "danger")
        return redirect(url_for("admin_panel"))

    if not title or not file_url or price <= 0:
        flash("Title, valid price, and file URL are required.", "danger")
        return redirect(url_for("admin_panel"))

    db = get_db()
    db.execute(
        """
        INSERT INTO products (title, description, price, file_url, created_at)
        VALUES (?, ?, ?, ?, ?)
        """,
        (title, description, price, file_url, datetime.utcnow().isoformat()),
    )
    db.commit()

    flash("Product added successfully.", "success")
    return redirect(url_for("admin_panel"))


@app.context_processor
def inject_now():
    return {"year": datetime.utcnow().year}


if __name__ == "__main__":
    with app.app_context():
        init_db()
    app.run(host="0.0.0.0", port=5000, debug=True)
