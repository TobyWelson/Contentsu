<template>
    <div class="thumb">
      <v-img :src="getThumURL" alt="alt here..."/>
      <div class="gard" />
    </div>
</template>

<script>
export default {
  props: {
    videoUrl: {
      type: String,
      required: true
    }
  },
  computed: {
    getThumURL : function() {
      var youtuResult = this.videoUrl.match(/youtu\.be/);
      var youtubeResult = this.videoUrl.match(/youtube\.com/);
      var tiktokResult = this.videoUrl.match(/tiktok\.com/);
      if (youtuResult != null) {
        var videoId = this.videoUrl.split('/')[3];
        return "http://img.youtube.com/vi/" + videoId + "/mqdefault.jpg";
      } else if (youtubeResult != null) {
        var spritId = this.videoUrl.split('v=')[1];
        var videoId = spritId.split('&')[0];
        return "http://img.youtube.com/vi/" + videoId + "/mqdefault.jpg";
      } else if (tiktokResult != null)  {
        return require('../../img/tiktok.png');
      }
      return "";
    },
  }
}
</script>