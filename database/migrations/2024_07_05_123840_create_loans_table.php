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
            $table->date('issued_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('returned_date')->nullable();
            $table->decimal('fine_amount', 10, 2)->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
