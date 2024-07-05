<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('member_id');
            $table->date('issued_date');
            $table->date('return_date')->nullable();
            $table->boolean('is_returned')->default(false);
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books');
            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
