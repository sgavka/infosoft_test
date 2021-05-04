<?php /** @noinspection DuplicatedCode */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Init extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('login', 30);
            $table->string('email', 191);
            $table->string('password', 191);
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->double('balance')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->integer('wallet_id')->unsigned();
            $table->foreign('wallet_id')->references('id')->on('wallets')->cascadeOnDelete();
            $table->double('invested')->default(0);
            $table->double('percent')->default(0);
            $table->smallInteger('active')->default(0);
            $table->smallInteger('duration')->default(0);
            $table->smallInteger('accrue_times')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->integer('wallet_id')->unsigned();
            $table->foreign('wallet_id')->references('id')->on('wallets')->cascadeOnDelete();
            $table->integer('deposit_id')->unsigned()->nullable()->default(null);
            $table->foreign('deposit_id')->references('id')->on('deposits')->cascadeOnDelete();
            $table->string('type', 30);
            $table->double('amount')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('users');
    }
}
