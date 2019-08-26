<style scoped>
  .aspect_meta{
    width:25px;
    padding:5px;
    float:left;
  }
</style>

<template>
  <div :id="control_drawer_id" @mouseleave="mouseLeave" class="control_drawer">
    <div @click="openDrawer" :id="toggle_id" class="aspect_meta">
      <i class="fa fa-cogs" aria-hidden="true"></i>
    </div>
    <div :id="aspect_controls_id" style="display:none;">
      <button @click="edit_button_click" class="btn btn-primary">Edit</button>
      <button @click="delete_button_click" class="btn btn-danger">Delete</button>
      Added: {{ display_date }}
    </div>
  </div>
</template>


<script>
import moment from 'moment';
export default {
    components: {},
    mixins: [],
    data () {
        return {
          name: 'aspect-control-drawer'
         }
    },
    props: [
      'aspectId',
      'createdAt'
    ],
    computed: {
        control_drawer_id: function(){
          return 'aspect_control_drawer_' + this.aspectId;
        },
        toggle_id: function(){
          return 'aspect_settings_toggle_' +  this.aspectId;
        },
        aspect_controls_id: function(){
          return 'aspect_controls_' + this.aspectId;
        },
        display_date: function(){
           var posted_date = moment.utc(this.createdAt);
           return posted_date.local().format("MMM D, YYYY h:mm A");
        }
    },
    methods: {
      edit_button_click: function(){
        window.location.href = '/aspect/' + this.aspectId + '/edit';
      },
      delete_button_click: function(){
        if (confirm('Are you sure you want to do that?')){
          window.location.href = '/aspect/' + this.aspectId + '/delete';
        }
      },
      openDrawer: function (){
        this.$jquery("#aspect_controls_" + this.aspectId).show();
      },
      mouseLeave: function(){
        this.$jquery("#aspect_controls_" + this.aspectId).hide();
      }
    }
};
</script>
