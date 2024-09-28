<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing 'id' column
            $table->integer('Emp_Code')->unique(); // Ensures unique values for 'Emp_Code'
            $table->string('Employee_Title', 4);
            $table->string('Employee_Name', 33);
            $table->string('Department', 34);
            $table->string('Designation', 52);
            $table->string('Grade', 7);
            $table->string('Region', 7);
            $table->string('Location', 31);
            $table->string('Gender', 6);
            $table->dateTime('Date_of_Joining');
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
