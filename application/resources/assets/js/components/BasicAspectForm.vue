<style scoped>

</style>

<template>
  <el-form ref="form" :model="form" label-width="120px">
    <el-form-item label="Title">
      <el-input v-model="form.title"></el-input>
    </el-form-item>

    <el-form-item label="Aspect Data">
      <el-input type="textarea" v-model="form.aspect_data"></el-input>
    </el-form-item>

    <el-form-item label="Aspect Source">
      <el-input v-model="form.aspect_source"></el-input>
    </el-form-item>

    <el-form-item label="Visibility">
      <el-switch on-text="Hide" off-text="Show" v-model="form.hidden"></el-switch>
    </el-form-item>

    <span v-if="form.file_upload" >
      <el-upload
        class="upload-demo"
        ref="upload"
        action="https://jsonplaceholder.typicode.com/posts/"
        :auto-upload="false">

        <el-button slot="trigger" size="small" type="primary">select file</el-button>
        <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">upload to server</el-button>
        <div class="el-upload__tip" slot="tip">jpg/png files with a size less than 500kb</div>
      </el-upload>
    </span>

    <el-form-item>
      <el-button type="primary" @click="onSubmit">Save Aspect</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
    data() {
      return {
        form: {
          subject_id: this.subjectId,
          aspect_type_id: this.aspectTypeId,
          title: '',
          aspect_data: '',
          file_upload: false,
          aspect_source: '',
          aspect_notes: '',
          hidden: false,
          aspect_object: null
        }
      }
    },
    props: [
      'aspect',
      'subjectId',
      'aspectTypeId'
    ],
    computed: {
      aspect_from_json: function(){
        console.log(this.aspect);
        return JSON.parse(this.aspect);
      }
    },
    methods: {
      parseAspect(json_text){
        var aspectObject = JSON.parse(json_text);
        this.title = aspectObject.title;
        //this.aspect_object = aspectObject;
        console.log(aspectObject);
      },
      onSubmit() {
        console.log('submit!');
        console.log(this.form);
      },
      submitUpload(){
        return;
      }
    }
  }
</script>
