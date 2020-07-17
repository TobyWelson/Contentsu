<template>
  <!-- <youtube :video-id="getId" /> -->
  <iframe
    class="youtube_view"
    :src="`http://www.youtube.com/embed/${getId}`"
    frameborder="0"
    allowfullscreen/>
</template>

<script>
// import Vue from 'vue'
// import VueYoutube from 'vue-youtube'
// Vue.use(VueYoutube)
export default {
  props: {
    videoUrl: {
      type: String,
      required: true
    }
  },
  computed: {
    getId : function() {
      var youtuResult = this.videoUrl.match(/youtu\.be/);
      var youtubeResult = this.videoUrl.match(/youtube\.com/);
      if (youtuResult != null) {
        return this.videoUrl.split('/')[3];
      } else if (youtubeResult != null) {
        var spritId = this.videoUrl.split('v=')[1];
        return spritId.split('&')[0];
      }
      return "";
    }
  }
}
</script>