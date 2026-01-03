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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('province_id')->nullable()->after('customer_address');
            $table->string('province_name')->nullable()->after('province_id');
            $table->string('city_id')->nullable()->after('province_name');
            $table->string('city_name')->nullable()->after('city_id');
            $table->string('district_id')->nullable()->after('city_name');
            $table->string('district_name')->nullable()->after('district_id');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('total_amount');
            $table->string('courier')->nullable()->after('shipping_cost');
            $table->string('courier_service')->nullable()->after('courier');
            $table->string('tracking_number')->nullable()->after('courier_service');
            $table->string('free_shipping_code')->nullable()->after('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'province_id',
                'province_name',
                'city_id',
                'city_name',
                'district_id',
                'district_name',
                'shipping_cost',
                'courier',
                'courier_service',
                'tracking_number',
                'free_shipping_code'
            ]);
        });
    }
};
