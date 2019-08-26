<template>

    <el-autocomplete
      id="query"
      name="query"
      class="inline-input"
      v-model="input_value"
      :fetch-suggestions="querySearch"
      placeholder="Search"
      :trigger-on-focus="false"
      @select="handleSelect"
    >
    </el-autocomplete>

</template>

<script>
  export default {
      data() {
        return {
          input_value: ''
        };
      },
      props: ['initial_value'],
      methods: {
        querySearch(queryString, cb) {
          jQuery.getJSON( "/subject/autocomplete?term="+this.input_value, function( data ) {
            cb( data );
          });
        },
        mounted() {
          this.input_value = this.initial_value;
        }
      }
  }
</script>
