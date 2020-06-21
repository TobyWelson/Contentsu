<template>
    <img :src="getThumURL" alt="alt here..." />
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
        this.videoId = this.videoUrl.split('v=')[1];
      }
      return "http://img.youtube.com/vi/" + this.videoId + "/mqdefault.jpg";
    }
  }
}
</script>