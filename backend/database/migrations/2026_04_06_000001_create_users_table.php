<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['user', 'support', 'manager', 'super_admin'])->default('user');
            $table->string('referral_code')->unique();
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('wallet_balance', 12, 2)->default(0);
            $table->string('avatar_url')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
