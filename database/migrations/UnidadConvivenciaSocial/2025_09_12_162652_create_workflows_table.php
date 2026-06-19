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
        Schema::connection('unidad-convivencia-social')->create('workflows', function (Blueprint $table) {
            $table->id();
            $table->text('observacion')->nullable();
            $table->integer('user_id')->nullable();
            $table->foreignId('expediente_id')->constrained();
            $table->foreignId('estado_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('unidad-convivencia-social')->dropIfExists('workflows');
    }
};
