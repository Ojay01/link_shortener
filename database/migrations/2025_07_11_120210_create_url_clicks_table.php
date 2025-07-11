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
        Schema::create('url_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('url_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->string('referer', 500)->nullable();
            $table->timestamp('clicked_at');
            $table->timestamps();
            
            $table->index(['url_id', 'clicked_at']);
            $table->index('clicked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_clicks');
    }
};
