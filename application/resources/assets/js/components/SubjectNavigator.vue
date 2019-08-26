<style scoped>

</style>

<template>
  <div>
    <el-tree
      :data="data"
      :props="defaultProps"
      @node-click="handleNodeClick"
      @current-change="handleCurrentChange"
      >
    </el-tree>
    <input type="hidden" :value="subjectId">
  </div>
</template>


<script>
export default {
  data() {
    return {
      data: this.menu,
      subjectId: -1,
      defaultProps: {
        children: 'children',
        label: 'label',
      }
    };
  },
  props: ['menu', 'currentlySelected'],
  methods: {
    handleNodeClick(data) {
      this.subjectId = data.value
      //console.log(data.value);
    },
    handleCurrentChange(data){
      this.subjectId = data.value
      console.log('CURRENT CHANGED!'+data.value);
      window.location.href = '/subject/'+data.value;
    }
  }
};
</script>
