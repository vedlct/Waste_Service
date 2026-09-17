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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('status', 40)->default('draft');
            $table->string('payment_status', 40)->default('unpaid');
            $table->string('payment_option', 40)->default('pay_now');
            $table->date('collection_date')->nullable();
            $table->boolean('is_saturday_collection')->default(false);
            $table->unsignedSmallInteger('notice_minutes')->nullable();
            $table->boolean('access_surcharge_acknowledged')->default(false);
            $table->string('restricted_access', 20)->nullable();
            $table->text('access_restrictions')->nullable();
            $table->text('large_items')->nullable();
            $table->text('collection_notes')->nullable();
            $table->string('currency', 3)->default('GBP');
            $table->unsignedInteger('subtotal_pence')->default(0);
            $table->unsignedInteger('discount_pence')->default(0);
            $table->unsignedInteger('extra_charges_pence')->default(0);
            $table->unsignedInteger('vat_pence')->default(0);
            $table->unsignedInteger('total_pence')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['collection_date', 'status']);
            $table->index(['payment_status', 'payment_option']);
            $table->index(['service_id', 'status']);
            $table->index('user_id');
        });

        Schema::create('booking_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('type', 40);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('mobile', 40)->nullable();
            $table->string('email')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('county')->nullable();
            $table->string('postcode', 20);
            $table->string('country', 80)->default('United Kingdom');
            $table->timestamps();

            $table->unique(['booking_id', 'type']);
            $table->index(['type', 'postcode']);
            $table->index('email');
            $table->index('phone');
        });

        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('service_item_id')->nullable()->constrained('service_items')->nullOnDelete();
            $table->foreignId('load_package_id')->nullable()->constrained('load_packages')->nullOnDelete();
            $table->foreignId('extra_charge_id')->nullable()->constrained('extra_charges')->nullOnDelete();
            $table->string('line_type', 40);
            $table->string('catalogue_sku')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('unit_price_pence');
            $table->unsignedInteger('vat_rate_basis_points')->default(2000);
            $table->unsignedInteger('line_total_pence');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'line_type']);
            $table->index('service_item_id');
            $table->index('load_package_id');
            $table->index('extra_charge_id');
            $table->index('catalogue_sku');
        });

        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('provider', 80)->nullable();
            $table->string('provider_reference')->nullable();
            $table->string('status', 40)->default('pending');
            $table->string('type', 40)->default('payment');
            $table->string('currency', 3)->default('GBP');
            $table->unsignedInteger('amount_pence');
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'status']);
            $table->index(['provider', 'provider_reference']);
            $table->index(['status', 'paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
        Schema::dropIfExists('booking_items');
        Schema::dropIfExists('booking_addresses');
        Schema::dropIfExists('bookings');
    }
};
