<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use imonroe\crps\AspectType;

class AddNetworkAspects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $aspect_type = new AspectType;
      $aspect_type->aspect_name = 'API Result';
      $aspect_type->aspect_description = 'The cached results of a third-party API call.';
      $aspect_type->is_viewable = 0;
      $aspect_type->save();

      $aspect_type = new AspectType;
      $aspect_type->aspect_name = 'RSS Feed';
      $aspect_type->aspect_description = 'An RSS feed aspect.';
      $aspect_type->is_viewable = 1;
      $aspect_type->save();

      $aspect_type = new AspectType;
      $aspect_type->aspect_name = 'Webpage';
      $aspect_type->aspect_description = 'A link to another web page.';
      $aspect_type->is_viewable = 1;
      $aspect_type->save();

      $aspect_type = new AspectType;
      $aspect_type->aspect_name = 'Web Scraper';
      $aspect_type->aspect_description = 'A simple web scraper to pull elements off another public web page.';
      $aspect_type->is_viewable = 1;
      $aspect_type->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
