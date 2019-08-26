<style >

#coldreader_codex__stage{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap; 
}

.codex_col{
    flex-basis: 100%;
    max-height: 300px;   
    overflow: auto;
}

@media screen and (min-width: 800px) {
    .codex_col{
        flex: 1;
        max-height: 100%;
    }
    ._25 {
        flex: 2.5;
    }
    ._50 {
        flex: 5;
    }
    ._33{
        flex: 3.3;
    }
    ._66{
        flex: 6.6;
    }
}

#coldreader_codex__subject_type_tree{
    
}

#coldreader_codex__subject_list{
    max-height: 100%;
}

.el-tree{
    background-color: rgba(0, 0, 0, .2) !important;
    width: 100%;
}

.el-tree-node.is-current>.el-tree-node__content{
    background-color:  rgba(0, 0, 0, .1) !important;
}

.el-tree-node__content:hover{
    background-color:  rgba(0, 0, 0, .1) !important;
}

.codex_buttons{
    margin-top: 5px;
    margin-bottom: 5px;
}

#subject_stage{
    padding: 1em;
    background-color: rgba(255, 255, 255, .1);
} 

</style>



<template>
    <div id="coldreader_codex__stage">
        <div id="coldreader_codex__subject_type_tree" class="codex_col _33">
            
            <div class="coldreader_codex__header" style="background-color:rgba(0,0,0,.3); padding:1em;">
                <span class="h4" style="float:left;">Subject Type: <strong>{{ subjectTypeName }}</strong></span>
                <span style="float:right;">
                    <a href="/subject_type/create" class="btn btn-default codex_buttons" data-toggle="tooltip" title="Add a new Subject Type"><i class="fa fa-plus" aria-hidden="true"></i></a>
                    <a :href="'/subject_type/' + subjectTypeId + '/edit'" class="btn btn-default codex_buttons" data-toggle="tooltip" title="Edit this Subject Type"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>
                </span>
                <div style="clear:both;"></div>
            </div>
            
            <div id="coldreader_codex__tree"> 
                <el-tree 
                    :data="codex" 
                    :props="defaultProps" 
                    accordion 
                    @node-click="handleNodeClick" 
                    empty-text="Empty" 
                    node-key="value"
                    :highlight-current="true"
                    :current-node-key="subjectTypeId"
                    :default-expanded-keys="[subjectTypeId]"
                    :default-checked-keys="[subjectTypeId]"
                    >
                </el-tree>
            </div>
            
        </div>

        <div id="coldreader_codex__subject_list" class="codex_col _66">

            <div class="coldreader_codex__header" style="background-color:rgba(0,0,0,.3); padding:1em;">
                <span class="h4" style="float:left;">Subjects:</span>
                <span style="float:left; margin-left:2em;">
                    <a :href="'/subject/create/'+subjectTypeId" class="btn btn-default codex_buttons" style="margin-left:2em;" data-toggle="tooltip" title="Add a new Subject">New Subject</a>    
                </span>
                <div style="clear:both;"></div>
            </div>

            <div id="subject_stage">
                <div v-html="ajaxPlaceholder" style="text-align: center; margin:auto;"></div>
                <p v-for="subject in subjects">
                    <a :href="'/subject/' + subject.id">{{ subject.name }}</a><br />
                    <span style="margin-left:.5em;"><em>{{ subject.description }}</em></span>
                </p>
            </div>

        </div>

        <div style="clear:both;"></div>
    </div>
</template>



<script>
export default {
  data() {
    return {
      codex: [],
      subjects: [],
      subjectTypeId: -1,
      subjectTypeName: '',
      defaultProps: {
        children: 'children',
        label: 'label',
      },
      ajaxPlaceholder: ''
    };
  },
  props: ['currentlySelected'],
  created() {
        this.initialize();
  },
  methods: {
    initialize(){
        this.loadCodex();
        if (typeof( this.currentlySelected ) !== "undefined") {
            this.subjectTypeId = this.currentlySelected;
        }
        this.loadSubjects();
    },
    loadCodex(){
        var self=this;
        this.$axios.post('/subject_type/ajax/list')
            .then(function(response){
                self.codex = response.data;
                if (typeof( self.currentlySelected ) !== undefined) {
                    self.subjectTypeName = self.getSubjectTypeName( self.codex );
                }
            })
            .catch(function(error){
                console.log(error);
            });
    },
    getSubjectTypeName( checkArray ){
        var output = false;
        for (var i=0; i < checkArray.length; i++){
            if ( output == false ){
                if ( checkArray[i].value == this.subjectTypeId ){
                    output =  checkArray[i].label;
                } else {
                    if ( checkArray[i].children !== undefined ) {
                        output = this.getSubjectTypeName( checkArray[i].children );
                    }
                }
            }
        }
        return output;
    },
    loadSubjects(){
        var self=this;
        self.ajaxPlaceholder = '<i class="fa fa-cog fa-spin fa-3x fa-fw"></i>';
        self.subjects = [];
        this.$axios.post('/subject_type/ajax/'+self.subjectTypeId+'/subjects')
            .then(function(response){
                self.subjects = response.data;
                self.ajaxPlaceholder = '';
            })
            .catch(function(error){
                console.log(error);
            });
    },
    handleNodeClick(data) {
      this.subjectTypeId = data.value;
      this.subjectTypeName = this.getSubjectTypeName( this.codex );
      this.loadSubjects();
    },
    handleCurrentChange(data){
    
    }
  }
};
</script>