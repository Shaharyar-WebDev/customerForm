<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->useCurrent();
            $table->string('name');
            $table->string('phone_number');
            $table->json('frequently_purchased_items')->nullable();
            $table->string('visit_frequency')->nullable();
            $table->string('category')->nullable();
            $table->enum('status', [
                'pending',
                'sent',
                'failed'
            ])
                ->default('pending');
            $table->text('remarks')->nullable();
            $table->text('error')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
