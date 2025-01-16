<?php
            
namespace App\Database\Migrations;

use App\Database\Migration;            
use App\Database\Blueprint;
use App\Database\Schema;
            
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
            
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
