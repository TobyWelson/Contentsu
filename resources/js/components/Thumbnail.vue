<template>
    <div class="thumb">
      <v-img :src="getThumURL" alt="alt here..."/>
      <div class="gard" />
    </div>
</template>

<script>
export default {
  props: [
    'videoUrl'
  ],
  data(){
      return {
          videoId: ''
      }
  },
  computed: {
    getThumURL : function() {
      var youbeResult = this.videoUrl.match(/youtu\.be/);
      var youtubeResult = this.videoUrl.match(/youtube\.com/);
      if (youbeResult != null) {
        this.videoId = this.videoUrl.split('/')[3];
      } else if (youtubeResult != null) {
        var spritId = this.videoUrl.split('v=')[1];
        this.videoId = spritId.split('&')[0];
      } else {
        return require('../../img/tiktok.png');
      }
      return "http://img.youtube.com/vi/" + this.videoId + "/mqdefault.jpg";
    },
  }
}
</script>