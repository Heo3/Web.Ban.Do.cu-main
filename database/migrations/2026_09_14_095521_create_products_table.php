<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255)->nullable();
            $table->unsignedBigInteger('price')->default(0); // Giá tiền VNĐ
            $table->longText('description')->nullable();
            $table->string('image', 500)->nullable(); // Ảnh đại diện chính
            $table->string('address', 255)->nullable(); // Địa chỉ chi tiết (quận/huyện, số nhà)
            $table->string('province', 100)->nullable(); // Tỉnh / Thành phố để lọc
            $table->string('phone', 20)->nullable(); // Số điện thoại liên hệ
            $table->enum('status', ['active', 'sold', 'hidden'])->default('active'); // Trạng thái tin
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
