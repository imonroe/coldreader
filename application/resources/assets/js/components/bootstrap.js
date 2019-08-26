
/*
 |--------------------------------------------------------------------------
 | Laravel Spark Components
 |--------------------------------------------------------------------------
 |
 | Here we will load the Spark components which makes up the core client
 | application. This is also a convenient spot for you to load all of
 | your components that you write while building your applications.
 */
 
 require('./home');
 require('./settings/profile/update-application-settings');
 require('./ColdreaderPlugin.js');

/* include the Element UI libraries */
import Element from 'element-ui';
import 'element-ui/lib/theme-default/index.css';
Vue.use(Element);

// my custom components
import coldreaderPlugin from './ColdreaderPlugin.js';
Vue.use(coldreaderPlugin);

Vue.component('subject-autocomplete-field', require('./SubjectAutocompleteField.vue'));
Vue.component('search-form', require('./SearchForm.vue'));
Vue.component('aspect-panel-controls', require('./AspectPanelControls.vue'));
Vue.component('aspect-control-drawer', require('./AspectControlDrawer.vue'));
Vue.component('add-aspect-jump-menu', require('./AddAspectJumpMenu.vue'));
Vue.component('basic-aspect-form', require('./BasicAspectForm.vue'));
Vue.component('subject-navigator', require('./SubjectNavigator.vue'));
Vue.component('subject-cascader', require('./SubjectCascader.vue'));
Vue.component('subject-type-navigator', require('./SubjectTypeNavigator.vue'));
Vue.component('subject-type-cascader', require('./SubjectTypeCascader.vue'));
Vue.component('subject-child-cascade-jump', require('./SubjectChildCascadeJump.vue'));
Vue.component('coldreader-codex', require('./ColdreaderCodex.vue'));
Vue.component('system-notifications', require('./SystemNotifications.vue'));

// From other packages
// require('./../../../../vendor/imonroe/cr_aspects_google/resources/assets/js/components/bootstrap.js');
require('./../../../../packages/imonroe/cr_network_aspects/resources/assets/js/components/bootstrap.js');

