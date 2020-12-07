<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostalCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('postal_codes', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->char('official_code', 11)->comment('全国地方公共団体コード');
            $table->char('postal_code5', 5)->comment('（旧）郵便番号（5桁）');
            $table->char('postal_code7', 7)->comment('郵便番号（7桁）');
            $table->string('kana_pref', 20)->comment('都道府県名カナ');
            $table->string('kana_city', 40)->comment('市区町村名カナ');
            $table->string('kana_town', 80)->comment('町域名カナ');
            $table->string('pref', 20)->comment('都道府県名');
            $table->string('city', 40)->comment('市区町村名');
            $table->string('town', 40)->comment('町域名');
            $table->unsignedTinyInteger('flag_doubleCode')->comment('一町域が二以上の郵便番号で表される場合の表示');
            $table->unsignedTinyInteger('flag_banchi')->comment('小字毎に番地が起番されている町域の表示');
            $table->unsignedTinyInteger('flag_chome')->comment('丁目を有する町域の場合の表示');
            $table->unsignedTinyInteger('flag_double_area')->comment('一つの郵便番号で二以上の町域を表す場合の表示');
            $table->unsignedTinyInteger('flag_update')->comment('更新の表示');
            $table->unsignedTinyInteger('flag_update_reason')->comment('変更理由');

            $table->timestamp('created_at')->nullable()->comment('作成日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');

            $table->index('official_code');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postal_codes');
    }
}
