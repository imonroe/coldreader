
/*
 * Load Vue & Vue-Resource.
 *
 */
if (window.Vue === undefined) {
    window.Vue = require('vue');

    window.Bus = new Vue();
}


require('./bootstrap');
require('./components/bootstrap');

window.Vue = require('vue');



var app = new Vue({
    el: '#app',
    mounted: function(){
        this.$coldreaderOnLoad();
    }
});
