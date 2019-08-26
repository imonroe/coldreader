<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DisableWebscraper extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $aspect_type = AspectType::where('aspect_name', '=', 'Web Scraper')->first();
        if ( !empty($aspect_type) ){
            $aspect_type->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $aspect_type = new AspectType;
        $aspect_type->aspect_name = 'Web Scraper';
        $aspect_type->aspect_description = 'A simple web scraper to pull elements off another public web page.';
        $aspect_type->is_viewable = 1;
        $aspect_type->save();
    }
}
