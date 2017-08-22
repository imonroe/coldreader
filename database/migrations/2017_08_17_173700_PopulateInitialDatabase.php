<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PopulateInitialDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// Create the basic Aspect Types
		DB::table('aspect_types')->insert([
			['aspect_name' => 'File Upload', 'aspect_description' => 'An uploaded file', 'is_viewable' => 1 ],
			['aspect_name' => 'Image', 'aspect_description' => 'An image file', 'is_viewable' => 1 ],
			['aspect_name' => 'Unformatted Text', 'aspect_description' => 'An unformatted blob of text', 'is_viewable' => 1 ],
			['aspect_name' => 'Markdown Text', 'aspect_description' => 'Markdown-formatted text', 'is_viewable' => 1 ],
			['aspect_name' => 'Formatted Text', 'aspect_description' => 'HTML-formatted text', 'is_viewable' => 1 ],
			['aspect_name' => 'API Result', 'aspect_description' => 'Results from a an API call to another system', 'is_viewable' => 0 ],
			['aspect_name' => 'Relationship', 'aspect_description' => 'A connection between two subjects', 'is_viewable' => 1 ],
			['aspect_name' => 'Lambda Function', 'aspect_description' => 'An aspect that performs some sort of calculation or function, rather than storing data', 'is_viewable' => 0 ],
		]);

		// create a subject type for the app configuration
		$configuration_subject_type_id = DB::table('subject_types')->insertGetId(
			['type_name' => 'Configuration', 'type_description' => 'These are configuration options for the Cold Reader app', 'parent_id' => -1]
		);

		// create a Front Page subject under the configuration subject type
		DB::table('subjects')->insert([
			['name' => 'Front Page Aspects', 'subject_type' => $configuration_subject_type_id],
		]);


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
