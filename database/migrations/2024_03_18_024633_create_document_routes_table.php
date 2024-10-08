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
        Schema::create('document_routes', function (Blueprint $table) {
            $table->id();
            $table->string('document_id');
            $table->foreignId('office_id');
            $table->foreignId('user_id');
            $table->dateTime('routed_on'); // from received_on
            $table->string('state');
            // $table->integer('action_order');
            $table->string('action')->nullable();
            $table->dateTime('acted_on')->nullable();
            // $table->foreignId('sender_id')->nullable();
            // $table->dateTime('sent_on')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_routes');
    }
};
