<style scoped>

</style>

<template>
  <div>
    <el-tree
      :data="data"
      node-key="value"
      highlight-current
      show-checkbox
      ref="tree"
      default-expand-all
      :render-after-expand="false"
      :props="defaultProps"
      :default-expanded-keys="expanded"
      :default-checked-keys="checked"
      :current-node-key="currentNode"
      @node-click="handleNodeClick"
      @current-change="handleCurrentChange"
      >
    </el-tree>
    <input type="hidden" :value="subjectTypeId">
  </div>
</template>


<script>
export default {
  data() {
    return {
      data: this.menu,
      subjectTypeId: -1,
      expanded: this.expandedKeys,
      currentNode: this.currentlySelected,
      checked: [this.currentlySelected],
      defaultProps: {
        children: 'children',
        label: 'label',
      }
    };
  },
  props: ['menu', 'currentlySelected', 'expandedKeys'],

  methods: {
    handleNodeClick(data) {
      this.subjectTypeId = data.value;
      //console.log(data.value);
    },
    handleCurrentChange(data){
      this.subjectTypeId = data.value;
      console.log('CURRENT CHANGED!'+data.value);
      window.location.href = '/subject_type/'+data.value;
    }
  },
  mounted: function () {
    this.$nextTick(function () {
      this.subjectTypeId = this.currentlySelected;
      this.$refs.tree.setCheckedKeys([this.currentlySelected]);
      this.$refs.tree.setCurrentKey(this.currentlySelected);
    })
  }
};
</script>
