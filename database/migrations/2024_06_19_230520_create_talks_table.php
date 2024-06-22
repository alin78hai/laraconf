<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Speaker;
use App\Enums\{ TalkLength, TalkStatus };

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('talks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Speaker::class);
            $table->string('title');
            $table->text('abstract');
            $table->string('length')->default(TalkLength::NORMAL);
            $table->string('status')->default(TalkStatus::SUBMITTED);
            $table->boolean('is_newtalk')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talks');
    }
};
