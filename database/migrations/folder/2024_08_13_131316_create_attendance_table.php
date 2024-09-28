<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name'); // Column for name
            $table->date('date'); // Column for date
            $table->time('checkin_time')->nullable(); // Column for check-in time
            $table->time('checkout_time')->nullable(); // Column for check-out time
            $table->timestamps(); // Optional, if you want created_at and updated_at fields

            $table->unique(['name', 'date']); // Ensure unique records per name and date
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance');
    }
}
