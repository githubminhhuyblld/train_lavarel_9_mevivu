<?php

use App\Constants\Enum\Featured;
use App\Constants\Enum\Status;
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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->enum('is_featured', [Featured::FEATURED, Featured::NORMAL])->default(Featured::NORMAL);
            $table->string('image')->nullable();
            $table->text('excerpt')->nullable();;
            $table->text('content')->nullable();;
            $table->timestamp('posted_at')->nullable();;
            $table->enum('status', [Status::ACTIVE, Status::INACTIVE, Status::DELETED])->default(Status::ACTIVE);
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
        Schema::dropIfExists('posts');
    }
};
