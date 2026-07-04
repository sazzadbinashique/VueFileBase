<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('bank_tran_id')->nullable()->after('transaction_id');
            $table->string('card_type')->nullable()->after('bank_tran_id');
            $table->string('card_no')->nullable()->after('card_type');
            $table->string('card_issuer')->nullable()->after('card_no');
            $table->string('card_brand')->nullable()->after('card_issuer');
            $table->string('card_issuer_country')->nullable()->after('card_brand');
            $table->string('currency_amount')->nullable()->after('card_issuer_country');
            $table->string('currency_type')->nullable()->after('currency_amount');
            $table->string('tran_date')->nullable()->after('currency_type');
            $table->string('val_id')->nullable()->after('tran_date');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn([
                'bank_tran_id', 'card_type', 'card_no', 'card_issuer',
                'card_brand', 'card_issuer_country', 'currency_amount',
                'currency_type', 'tran_date', 'val_id',
            ]);
        });
    }
};
