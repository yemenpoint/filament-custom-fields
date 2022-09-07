<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("filament_custom_fields", function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_type');
            $table->string('type')->default("text");
            $table->boolean('required')->default(false);
            $table->json('options')->nullable();
            $table->string('title');
            $table->string('hint')->nullable();
            $table->string('default_value')->nullable();
            $table->text('rules')->nullable();
            $table->boolean('show_in_columns')->default(false);
            $table->integer('column_span')->default(1);
            $table->string('order')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create("filament_custom_field_responses", function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('field_id');
            $table->foreign('field_id')->references('id')->on("filament_custom_fields");
            $table->unsignedInteger('model_id');
            $table->string('model_type');
            $table->longText('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::dropIfExists("filament_custom_fields");
        Schema::dropIfExists("filament_custom_field_responses");
    }
};
