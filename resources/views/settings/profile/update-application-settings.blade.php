@php

    $prefs_controller = new imonroe\crps\Http\Controllers\UserPreferencesController;

    $prefs_form = $prefs_controller->get_preference_form();

@endphp

<update-application-settings :user="user" inline-template>
    <div class="panel panel-default">
        <div class="panel-heading">Preferences</div>

        <div class="panel-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="form.successful">
                Your profile has been updated!
            </div>

            {!! $prefs_form !!}

            </form>
        </div>
    </div>
</update-application-settings>
