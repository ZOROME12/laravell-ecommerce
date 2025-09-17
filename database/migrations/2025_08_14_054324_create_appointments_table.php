<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone', 30);
            $table->string('email');
            $table->date('schedule_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->uuid('token')->unique();        // used in QR for verification
            $table->string('qr_code_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            // Optional: prevent duplicate bookings per email per date
            $table->unique(['email', 'schedule_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
