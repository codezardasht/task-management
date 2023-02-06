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
        Schema::create('status_boards', function (Blueprint $table) {
            $table->id();
            $table->integer('board_id');
            $table->string('name');
            $table->enum('is_assign', [0, 1])->default(0)->comment('for check this status can be assign');
            $table->string('role_ids')->nullable()->comment('this field is seperate by comma for handling multiple condition about status change');
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_boards');
    }
};
