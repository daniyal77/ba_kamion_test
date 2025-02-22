<?php

use App\Enums\BodyType;
use App\Enums\CarStatus;
use App\Enums\FuelType;
use App\Enums\GearboxType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // نام خودرو
            $table->string('model'); // مدل خودرو
            $table->integer('year'); // سال تولید
            $table->bigInteger('price'); // قیمت
            $table->integer('mileage')->nullable(); // کارکرد
            $table->string('color')->nullable(); // رنگ خودرو
            $table->text('description')->nullable(); // توضیحات کامل خودرو
            $table->json('image_urls')->nullable(); // آرایه URL تصاویر خودرو
            $table->boolean('is_new')->default(false); // صفر کیلومتر بودن
            $table->enum('gearbox_type',  GearboxType::getValues()); // نوع گیربکس
            $table->enum(
                'body_type', BodyType::getValues()); // نوع بدنه
            $table->enum('fuel_type', FuelType::getValues()); // نوع سوخت
            $table->enum('status', CarStatus::getValues())->default(CarStatus::Available->value); // وضعیت خودرو
            $table->unsignedBigInteger('brand_id'); // کلید خارجی به جدول برندها
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->timestamps(); // زمان ایجاد و به‌روزرسانی

            //todo سرچ های اصلی برکدوم اساس
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
