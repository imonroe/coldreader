<style></style>

<template>
    <div>

    </div>
</template>

<script>
export default {
    data () {
        return {
          alert: this.message
        };
    },
    props: [
      'notificationType',
      'message'
    ],
    computed: {
       'title': function(){
           if (this.notificationType == 'error'){
               return 'Error';
           } else {
               return 'Info';
           }
       } 
    },
    methods: {
        showMessage(){
            this.$notify({
                title: this.title,
                message: this.message,
                type: this.notificationType
            });
        }
    },
    created() {
        this.showMessage();
    }
};
</script>