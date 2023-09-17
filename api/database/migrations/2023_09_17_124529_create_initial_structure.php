<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('establishments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name', 100);
            $table->text('description');
            
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function(Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->uuid('establishment_id')->after('password')->nullable();

            $table->foreign('establishment_id')->references('id')->on('establishments');
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('facebook_link', 100)->nullable();
            $table->string('instagram_link', 100)->nullable();
            $table->string('whatsapp', 45)->nullable();
            $table->uuid('establishment_id');
            $table->json('opening_hours')->nullable();
            $table->json('payment_methods')->nullable();
            $table->string('localization', 70)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('image_cover_profile_location', 255)->nullable();

            $table->foreign('establishment_id')->references('id')->on('establishments');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ratings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->integer('price_stars')->nullable();
            $table->integer('environment_stars')->nullable();
            $table->integer('service_stars')->nullable();
            $table->integer('products_stars')->nullable();
            $table->uuid('establishment_id');
            $table->date('date_visit')->nullable();
            $table->text('comment')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('phone_number', 30)->nullable();
            $table->date('birthday')->nullable();
            $table->enum('feedback', ['positive', 'negative'])->default('positive');

            $table->foreign('establishment_id')->references('id')->on('establishments');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('establishment_id');
            $table->string('qr_code_image_path', 255);

            $table->foreign('establishment_id')->references('id')->on('establishments');

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('menu_id');
            $table->integer('likes')->default(0);
            $table->integer('not_likes')->default(0);
            $table->string('title', 255);
            $table->text('description');
            $table->string('cover_image_location', 255);
            $table->double('max_price')->nullable();
            $table->double('min_price');
            $table->string('currency', 20);
            $table->integer('portions');

            $table->foreign('menu_id')->references('id')->on('menus');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function(Blueprint $table) {
            $table->dropForeign('items_menu_id_foreign');
        });
        Schema::dropIfExists('items');

        Schema::table('menus', function(Blueprint $table) {
            $table->dropForeign('menus_establishment_id_foreign');
        });
        Schema::dropIfExists('menus');
        
        Schema::table('ratings', function(Blueprint $table) {
            $table->dropForeign('ratings_establishment_id_foreign');
        });
        Schema::dropIfExists('ratings');

        Schema::table('profiles', function(Blueprint $table) {
            $table->dropForeign('profiles_establishment_id_foreign');
        });
        Schema::dropIfExists('profiles');

        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('users_establishment_id_foreign');
            $table->dropColumn('establishment_id');
            $table->dropColumn('is_admin');
        });

        Schema::dropIfExists('establishments');
    }
};
