<style scoped>

</style>

<template>
  <el-select v-on:change="somethingSelected" v-model="value" placeholder="Add a new Aspect">
    <el-option
      v-for="item in option_list"
      :key="item.value"
      :label="item.label"
      :value="item.value">
    </el-option>
  </el-select>
</template>

<script>
export default {
    components: {},
    mixins: [],
    data () {
      return {
        value: ''
      }
    },
    mounted() {},
    props: [
      'optionList',
      'subjectId'
    ],
    computed: {
      option_list: function (){
        return this.optionList;
      }
    },
    methods: {
        somethingSelected: function(aspect_type_id){
          console.log('entered change method on jump menu');
          window.location.href = '/aspect/create/' + this.subjectId + '/type/' + aspect_type_id;
        }
    }
};
</script>
