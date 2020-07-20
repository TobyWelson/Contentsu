<template>
  <div>
    <iframe
      class="youtube_view"
      :class="{ 'tiktok_view': getVideoTypeTiktok }"
      :src="`${getUrl}`"
      frameborder="0"
      allowfullscreen=""/>
  </div>
</template>

<script>
import { MATCH_URL_YOUTU, MATCH_URL_YOUTUBE, MATCH_URL_TIKTOK } from '../util'
import { VIDEO_URL_YOUTUBE, VIDEO_URL_TIKTOK } from '../util'

export default {
  props: {
    videoUrl: {
      type: String,
      required: true
    }
  },
  computed: {
    getUrl : function() {
      if (this.videoUrl.match(MATCH_URL_YOUTU)) {
        return VIDEO_URL_YOUTUBE + this.videoUrl.split('/')[3];
      } else if (this.videoUrl.match(MATCH_URL_YOUTUBE)) {
        var spritId = this.videoUrl.split('v=')[1];
        return VIDEO_URL_YOUTUBE + spritId.split('&')[0];
      } else if (this.videoUrl.match(MATCH_URL_TIKTOK)) {
        var spritId = this.videoUrl.split('video/')[1];
        return VIDEO_URL_TIKTOK + spritId.split('?')[0];
      }
      return "";
    },
    getVideoTypeTiktok : function() {
      if (this.videoUrl.match(MATCH_URL_TIKTOK)) {
        return true;
      }
      return false;
    }
  }
}
</script>