<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rate')->between(1, 5);
            $table->string('review', 250);
            $table->enum('status', ['Pending Approval', 'Approved'])->default('Pending Approval');
            $table->date('experienced_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
