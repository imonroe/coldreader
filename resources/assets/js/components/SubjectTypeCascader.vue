<style >

  .el-cascader .el-input, .el-cascader .el-input__inner{
    background-color:#fff !important;
  }

</style>

<template>
  <div>
    <el-cascader
    :options="options"
    :show-all-levels="false"
    :change-on-select="true"
    v-model="selectedOptions"
    expand-trigger="hover"
    filterable
    placeholder="No Subject Type"
    @change="handleChange"
    class="input_cascader"></el-cascader>
    <input type="hidden" id="subject_type" name="subject_type" v-bind:value="currentValue">
  </div>
</template>

<script>
  export default {
    data() {
      return {
        options: this.menu,
        selectedOptions: this.stringifyArray( this.selectedArray() ),
        currentValue: this.setSelectedValue()
      };
    },
    props: ['menu', 'currentlySelected'],
    computed: { },
    methods: {
      handleChange(data){
        var last_element = this.selectedOptions[this.selectedOptions.length - 1];
        this.currentValue = last_element;
      },
      selectedArray(){
        if (this.currentlySelected[0] == ""){
          return ["-1"];
        } else {
          return this.currentlySelected;
        }
      },
      setSelectedValue(){
        return [ this.currentlySelected[this.currentlySelected.length - 1].toString() ];
      },
      stringifyArray(arr){
        var output = [];
        for(var i = 0, len = arr.length; i < len; i++){
          output[i] = arr[i].toString();
        }
        return output;
      }
    }
  };
</script>
