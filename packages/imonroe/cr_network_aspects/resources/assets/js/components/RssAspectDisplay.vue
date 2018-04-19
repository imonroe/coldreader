<style scoped>
.rss-item{
    padding: .1em;
    
}
.rss-byline{
    font-size: .75em;
    font-style: italic;
}
.lighten{
    opacity: 0.6;
}
hr{
    border: 0;
    height: 1px;
    background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
    margin: .25em;
}
</style>

<template>
    <div>
        <span v-if="loading"><center><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></center></span>
        <span v-else>
            <div class="rss-item" v-for="item in data"> 
                <a :href="item.link" target="_blank">{{ item.title }}</a><br/>
                <div class="rss-byline lighten">
                    <span v-if="item.author != null ">By: {{ item.author }} | </span>
                    <span>{{ formatDate( item.date )  }}</span>
                </div>
                <hr></hr>   
            </div>
        </span>
    </div>
</template>


<script>
import FeedParser from 'feedparser'
import stringToStream from 'string-to-stream'
import moment from 'moment'
export default {
  data() {
    return {
      data: [],
      feedUrl: '',
      itemCount: '',
      loading: true
    };
  },
  props: ['options'],
  computed: {
      initialConfiguration: function (){
        return JSON.parse(this.options);
      }
  },
  mounted: function(){
      var self = this;
      this.feedUrl = this.initialConfiguration.feed_url;
      this.itemCount = this.initialConfiguration.item_count;
      console.log('trying to get feed.');

      this.feedParse(this.feedUrl)
        .then(items => {
            self.loading = false;
            items.sort(function(a, b){
                return b.date - a.date;
            });
            self.data = items;
            self.$rejigger();
        })
        .catch(errors => {
            console.log(errors);
        });  
      console.log('attempt complete.');
  },
  methods: {
    actualCount(){
        if ( this.initialConfiguration.item_count > this.data.length){
            return this.data.length;
        } else {
            return this.initialConfiguration.item_count;
        }
    }, 
    formatDate(date) {
        return moment(date).format('MMMM Do, h:mm a');
    }, 
    handleNodeClick(data) {
      this.subjectId = data.value
      //console.log(data.value);
    },
    handleCurrentChange(data){
      this.subjectId = data.value
      console.log('CURRENT CHANGED!'+data.value);
      window.location.href = '/subject/'+data.value;
    },
    feedParse(url){
        this.loading = true;
        const feedparser = new FeedParser();
        var proxyUrl = "/rss_get_proxy?url=" + url;
        return this.$axios({ method: 'get', url: proxyUrl, timeout: 3000, crossdomain: true })
        .then( res => {
            // res.data.pipe(feedparser);
            stringToStream(res.data).pipe(feedparser);
        })
        .then( () => {
                var promise = new Promise((resolve, reject) => {
                const items = []
                feedparser.on('readable', function () {
                    const stream = this
                    let item
                    while ((item = stream.read())) {
                        items.push(item)
                    }
                })
                feedparser.on('end', () => {
                    resolve(items)
                })
                feedparser.on('error', err => {
                    reject(err)
                })
        })

        return Promise.all([promise])
            .then(feed => {
                return feed[0]
            })
            .catch(err => {
                throw err
            })
        })
        .catch(e => {
            console.log(e);
            //throw new Error()
        })
    }
  }

}

</script>
