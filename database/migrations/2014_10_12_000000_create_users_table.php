<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('privileges');
            $table->string('job');
            $table->rememberToken();
            $table->timestamps();
        });

        User::create([
            'name' => 'Jovan Lopez',
            'key' => '280469',
            'email' => 'everest.studio32@gmail.com',
            'password' => Hash::make('EverestH*me32'),
            'privileges' => 'MANAGER',
            'job' => 'ADMINISTRADOR PLATAFORMA',
        ]);

        User::create([
            'name' => 'Eduardo Gonzalez',
            'key' => 'EDUARDGNZ',
            'email' => 'e.gonzalez@unineuuni.edu.mx',
            'password' => Hash::make('Neuuni123*'),
            'privileges' => 'MANAGER',
            'job' => 'JEFE',
        ]);

        User::create([
            'name' => 'Michelle',
            'key' => 'MICH1234',
            'email' => 'ventas@goramexico.com',
            'password' => Hash::make('Gora123*'),
            'privileges' => 'MANAGER',
            'job' => 'JEFE DE DEPARTAMENTO',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
