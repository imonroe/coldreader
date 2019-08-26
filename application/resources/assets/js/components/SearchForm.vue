<template>
  <form class="form-inline my-2 my-lg-0" id="basic_search_form" method="POST" action="/search/results">
    <input type="hidden" name="_token" :value="csrf">
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
      <el-button slot="append" icon="el-icon-search" @click="submitSearch">
        <i class="fa fa-search" aria-hidden="true"></i>
      </el-button>
    </el-autocomplete>
  </form>
</template>

<script>
  export default {
      data() {
        return {
          links: [],
          csrf: '',
          input_value: ''
        };
      },
      methods: {
        querySearch(queryString, cb) {
          jQuery.getJSON( "/subject/autocomplete?term="+this.input_value, function( data ) {
            cb( data );
          });
        },
        handleSelect(item) {
          console.log(item);
        },
        submitSearch(){
          document.getElementById("basic_search_form").submit();
        }
      },
      mounted() {
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            this.csrf = token.content;
        } else {
            console.error('CSRF token not found for search form.');
        }
      }
  }
</script>
