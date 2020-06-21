<template>
    <youtube :video-id="getId" />
</template>

<script src="https://www.youtube.com/iframe_api"></script>
<script>
import { OK } from '../util'
import Vue from 'vue'
import VueYoutube from 'vue-youtube'

Vue.use(VueYoutube)

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
    getId : function() {
      var youbeResult = this.videoUrl.match(/youtu\.be/);
      var youtubeResult = this.videoUrl.match(/youtube\.com/);
      if (youbeResult != null) {
        this.videoId = this.videoUrl.split('/')[3];
      } else if (youtubeResult != null) {
        this.videoId = this.videoUrl.split('v=')[1];
      }
      return this.videoId;
    }
  }
}
</script>