<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('qty')->default(0);
            $table->unsignedBigInteger('security_stock')->default(0);
            $table->boolean('featured')->default(false);
            $table->boolean('is_visible')->default(false);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->enum('type', ['deliverable', 'downloadable'])->nullable();
            $table->boolean('backorder')->default(false);
            $table->boolean('requires_shipping')->default(false);
            $table->date('published_at')->nullable();
            $table->string('seo_title', 60)->nullable();
            $table->string('seo_description', 160)->nullable();
            $table->decimal('weight_value', 10, 2)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('weight_unit')->default('kg');
            $table->decimal('height_value', 10, 2)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('height_unit')->default('cm');
            $table->decimal('width_value', 10, 2)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('width_unit')->default('cm');
            $table->decimal('depth_value', 10, 2)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('depth_unit')->default('cm');
            $table->decimal('volume_value', 10, 2)->nullable()
                ->default(0.00)
                ->unsigned();
            $table->string('volume_unit')->default('l');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_products');
    }
};
