<template>
    <div>
      <span :id="reorder_id" class="reorder-handle" >
							<i class="fa fa-arrows" aria-hidden="true" ></i>
			</span>
      <span :id="toggle_id" v-on:click="toggle" style="margin-left: .5em;">
        <i :class="this.icon" aria-hidden="true"></i>
      </span>        
    </div>
</template>

<script>
export default {
    components: {},
    mixins: [],
    data () {
        return {
          name: 'aspect-panel-controls',
          toggle_id: 'toggle-' + this.aspectId,
          reorder_id: 'reorder-handle-' + this.aspectId,
          is_folded: this.initiallyFolded()
         }
    },
    props: [
      'aspectId',
      'folded'
    ],
    created() {
      this.csrf = window.axios.defaults.headers.common['X-CSRF-TOKEN'];
    },
    computed: {
      icon: {
        get: function (){ return this.getIcon(this.is_folded); },
        set: function (){ return; }
      }
    },
    methods: {
        getIcon: function(val){
          if ( val ){
            return 'fa fa-caret-left';
          } else {
            return 'fa fa-caret-down';
          }
        },
        initiallyFolded: function(){
          return (this.folded == 'true');
        },
        toggle: function (event) {
          console.log('toggle');
          var self = this;
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          if (this.is_folded){
            this.is_folded = false;
            this.icon = this.getIcon(this.is_folded);
            this.$jquery("#collapsable-"+this.aspectId).toggle("slow", function(){
              self.$rejigger();
            });
            this.$axios.post("/aspect/"+this.aspectId+"/fold");
            //$.post( "/aspect/"+this.aspectId+"/fold" );
          } else {
            this.is_folded = true;
            this.icon = this.getIcon(this.is_folded);
            this.$jquery("#collapsable-"+this.aspectId).toggle("slow", function(){
              self.$rejigger();
            });
            this.$axios.post("/aspect/"+this.aspectId+"/fold");
            //this.$jquery().post( "/aspect/"+this.aspectId+"/fold" );
          }
        }
    }
}
</script>
