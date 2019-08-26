Vue.component('update-application-settings', {
    props: ['user'],

    data() {
        return {
            form: new SparkForm({
                age: ''
            })
        };
    },

    mounted() {
        this.form.age = this.user.age;
    },

    methods: {
        update() {
            Spark.put('/settings/profile/details', this.form)
                .then(response => {
                    Bus.$emit('updateUser');
                });
        }
    }
});
