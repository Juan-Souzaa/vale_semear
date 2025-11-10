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
        Schema::create('reunioes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['diretoria', 'assembleia', 'comissao', 'outro'])->default('diretoria');
            $table->dateTime('data_hora');
            $table->string('local')->nullable();
            $table->enum('status', ['agendada', 'confirmada', 'em_andamento', 'concluida', 'cancelada'])->default('agendada');
            $table->foreignId('organizador_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reunioes');
    }
};
