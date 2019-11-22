<style scoped>
</style>

<template>
  <el-cascader
   expand-trigger="hover"
   :options="options"
   placeholder="Jump to child subject"
   @change="handleChange">
 </el-cascader>
</template>

<script>
export default {
    data() {
      return {
        options: this.menu,
      };
    },
    props: ['menu'],
    methods: {
      handleChange(value) {
        var subject_id = value[0];
        console.log(subject_id);
        window.location.href = '/subject/'+subject_id;
      }
    }
  };
</script>
