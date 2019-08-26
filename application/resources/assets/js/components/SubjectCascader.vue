<style scoped>

</style>

<template>
  <div>
    <el-cascader
    :options="options"
    :show-all-levels="false"
    :change-on-select="true"
    expand-trigger="hover"
    filterable
    v-model="selectedOptions"
    placeholder="No Parent Subject"
    @change="handleChange"
    ></el-cascader>
    <input type="hidden" id="parent_id" name="parent_id" v-bind:value="selectedValue">
  </div>
</template>

<script>
  export default {
    data() {
      return {
        options: this.menu,
        selectedOptions: this.selectedArray()
      };
    },
    props: ['menu', 'currentlySelected'],
    computed: {
      selectedValue: function(){
        return this.selectedOptions[this.selectedOptions.length - 1];
      }
    },
    methods: {
      handleChange(data){
        var last_element = this.selectedOptions[this.selectedOptions.length - 1];
        this.selectedValue = last_element;
      },
      selectedArray(){
        console.log("DEEEEEEEBUG!!  currentlySelected="+this.currentlySelected);
        if (this.currentlySelected[0] == ""){
          return ["-1"];
        } else {
          return this.currentlySelected;
        }
      },
      setSelectedValue(){
        return this.currentlySelected[this.currentlySelected.length - 1];
      }
    }
  };
</script>
