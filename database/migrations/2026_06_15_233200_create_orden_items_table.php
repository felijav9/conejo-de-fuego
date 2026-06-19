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
        Schema::connection('desarrollo-social')->create('orden_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('orden_id')
                ->constrained('ordenes')
                ->cascadeOnDelete();

            $table->foreignId('producto_id')
                ->constrained();

            $table->integer('cantidad');

            $table->decimal('precio_unitario', 10, 2);

            $table->decimal('subtotal', 10, 2);

            $table->text('nota')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_items');
    }
};
