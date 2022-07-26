<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->xForeignId('parent_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('type')->index();
            $table->string('title')->index();
            $table->string('slug')->index();
            $table->json('meta')->nullable();
            $table->text('description')->nullable();
            $table->integer('weight')->default(1);
            $table->timestamps();

            $table->unique(['type', 'title']);
            $table->unique(['type', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomies');
    }
};
