<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyPasswordResetsTable extends Migration
{
    public function up()
    {
        Schema::create('property_password_resets', function (Blueprint $table) {
            $table->string('business_email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_password_resets');
    }
}
