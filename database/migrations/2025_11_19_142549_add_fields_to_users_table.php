<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('account_type', ['private', 'pro'])->default('private');
            $table->string('address')->nullable();

            // Pour les comptes pro
            $table->string('company_name')->nullable();
            $table->string('cfe_number')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_type', 'address', 'company_name', 'cfe_number']);
        });
    }
};
