<?php
namespace App\Seeders;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use imonroe\crps\Aspect;
use imonroe\crps\Subject;
use imonroe\crps\AspectType;
use imonroe\ana\Ana;

class NewAccountSeeder{

  public static function populate(int $user_id){
    // set up the user's storage folder.
    $user = \App\User::findOrFail($user_id);
    $user->storage = Ana::generateStrongPassword(8, false, 'l');

    // set up the initial settings array.
    $user->settings = json_encode(array());
    $user->save();

    // create the dashboard, and add a welcome subject.
    $dashboard = new Subject;
    $dashboard->name = 'Dashboard';
    $dashboard->subject_type = -1;
    $dashboard->user = $user_id;
    $dashboard->editable = 0;
    $dashboard->description = 'This is the primary dashboard.';
    $dashboard->save();

    // create the CachedAspects subject, and set it to hidden.
    $cache = new Subject;
    $cache->name = 'CachedAspects';
    $cache->subject_type = -1;
    $cache->user = $user_id;
    $cache->editable = 0;
    $cache->hidden = 1;
    $cache->description = 'A hidden subject for holding cached data from APIs, etc.';
    $cache->save();

    $formatted_aspect_type = AspectType::where('aspect_name', '=', 'Formatted Text')->first();

    $welcome = new Aspect;
    $welcome->title = 'Welcome!';
    $welcome->aspect_data = '<p>Welcome to Coldreader.</p><p>Feel free to click around.</p>';
    $welcome->aspect_type = $formatted_aspect_type->id;
    $welcome->user = $user_id;
    $welcome->display_weight = 99;
    $welcome->save();

    $dashboard->aspects()->attach($welcome->id);
  }
}
