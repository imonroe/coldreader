import jQuery from 'jquery';
import jQueryUI from 'jquery-ui';
import masonry from 'masonry-layout';
import axios from 'axios';

export default{
    install: function (Vue, options) {

        Vue.prototype.$jquery = jQuery;
        Vue.prototype.$jqueryui = jQueryUI;
        Vue.prototype.$masonry = masonry;
        Vue.prototype.$axios = axios;

        Vue.prototype.$rejigger = function(strMsg=''){
            this.$nextTick(function(){
                var container = document.querySelector('#grid');
                if (container !== null){
                    // Don't run if we don't have a grid to lay out. this avoids console errors on settings pages.
                    var msnry = new this.$masonry( container, {
                        itemSelector: '.aspect_display',
                        columnWidth: '.col-xs-12',
                        percentPosition: true,
                        transitionDuration: '0.5s',
                        stagger: 30
                    });
                    msnry.reloadItems();
                    msnry.layout();
                    console.log('Vue layout rejiggered. ' + strMsg );
                }
            });
        }

        Vue.prototype.$coldreaderOnLoad = function(){

            // We are trying to mock the functionality of a jQuery on document ready function.
            // We will call this function from the master vue instance so that it will run once when the app is mounted.

            var self = this;
            self.$rejigger('one-time onLoad.');

            // bind a listener to the window to rejigger on resize.
            self.$jquery(window).bind('resize', function () {
                self.$rejigger('on window resize.');
            });

            self.$axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';    
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                self.$axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            } else {
                console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
            }

            self.$jquery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });

            // Support for drag and drop reordering.
            self.$jquery(".sortable").sortable({
                handle: '.reorder-handle',
                placeholder: "ui-sortable-placeholder",
                refreshPositions: true,
                opacity: 0.6,
                scroll: true,
                forcePlaceholderSize: true,
                update: function (event, ui) {
                    console.log(ui);
                    var data = self.$jquery(this).sortable('serialize');
                    self.$axios.post("/subject/aspect_sorter", data);
                    self.$rejigger();
                }
            });

            // Anything that's marked with a confirmation class gets an alert to make sure that we know what we're doing.
            self.$jquery('.confirmation').on('click', function () {
                return confirm('Are you sure you want to do that?');
            });

        }


        Vue.mixin({
            computed:{
                csrfToken: function(){
                    let token = document.head.querySelector('meta[name="csrf-token"]');
                    return token.content; 
                }
            }
        })

        Vue.prototype.$coldreaderTheme = function(){
            
        }

    }
}