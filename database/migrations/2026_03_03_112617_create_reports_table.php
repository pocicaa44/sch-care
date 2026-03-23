<?php

use Illuminate\Database\Eloquent\SoftDeletes;
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
    Schema::create('reports', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
      $table->string('title');
      $table->string('location');
      $table->text('description');
      $table->boolean('is_anonymous')->default(false);
      $table->enum('status', ['pending', 'diproses', 'ditolak', 'selesai'])->default('pending');

      // soft deletes independed
      $table->timestamp('deleted_by_user_at')->nullable();
      $table->timestamp('deleted_by_admin_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('reports');
    // Schema::table('reports', function (Blueprint $table) {
    //   $table->softDeletes();
    // });
  }
};
