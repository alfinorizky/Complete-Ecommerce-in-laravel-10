<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductSnapshotToCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Tambah kolom snapshot
            $table->string('product_name')->nullable()->after('product_id');
            $table->string('product_photo')->nullable()->after('product_name');

            // Ubah product_id jadi nullable dulu sebelum SET NULL bisa bekerja
            $table->unsignedBigInteger('product_id')->nullable()->change();

            // Drop foreign key lama lalu buat ulang dengan SET NULL
            $table->dropForeign(['product_id']);
            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'product_photo']);

            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('CASCADE');
        });
    }
}