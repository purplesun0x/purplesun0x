import { createContext, useContext, useMemo, useState } from 'react';

const StoreContext = createContext(null);

export function StoreProvider({ children }) {
  const [cart, setCart] = useState([]);
  const [wishlist, setWishlist] = useState([]);

  const addToCart = (product) => {
    setCart((prev) => {
      const match = prev.find((x) => x.id === product.id);
      if (match) return prev.map((x) => (x.id === product.id ? { ...x, qty: x.qty + 1 } : x));
      return [...prev, { ...product, qty: 1 }];
    });
  };

  const updateQty = (id, qty) => setCart((prev) => prev.map((item) => (item.id === id ? { ...item, qty } : item)));
  const removeFromCart = (id) => setCart((prev) => prev.filter((item) => item.id !== id));
  const toggleWishlist = (product) => {
    setWishlist((prev) => (prev.some((x) => x.id === product.id) ? prev.filter((x) => x.id !== product.id) : [...prev, product]));
  };

  const cartTotal = cart.reduce((sum, item) => sum + Number(item.price) * item.qty, 0);

  const value = useMemo(() => ({ cart, wishlist, addToCart, updateQty, removeFromCart, toggleWishlist, cartTotal }), [cart, wishlist]);
  return <StoreContext.Provider value={value}>{children}</StoreContext.Provider>;
}

export function useStore() {
  return useContext(StoreContext);
}
