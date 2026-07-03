<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('short_url_clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('short_url_id');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('country', 2)->nullable();
            $table->timestamp('clicked_at');
            $table->timestamps();

            $table->index(['short_url_id', 'clicked_at']);
            $table->index('clicked_at');

            $table->foreign('short_url_id')
                ->references('id')
                ->on('short_urls')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_url_clicks');
    }
};
