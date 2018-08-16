<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebhooksTable extends Migration
{
    public function up()
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->increments('id');

            $table->string('monzo_account_id'); // Actual Monzo account/login
            $table->string('monzo_webhook_id');

            $table->string('ynab_refresh_token', 500);
            $table->string('ynab_budget_id');
            $table->string('ynab_account_id'); // A bank account/'holder' of transactions, not the users login/account

            $table->integer('count');

            $table->index('monzo_account_id');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('webhooks');
    }
}
