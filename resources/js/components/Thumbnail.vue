<template>
    <div class="thumb">
      <v-img :src="getThumURL" alt="alt here..."/>
      <div class="gard" />
    </div>
</template>

<script>
import { MATCH_URL_YOUTU, MATCH_URL_YOUTUBE, MATCH_URL_TIKTOK } from '../util'
import { THUMBNAIL_URL_YOUTUBE, THUMBNAIL_URL_YOUTUBE_IMAGE } from '../util'

export default {
  props: {
    videoUrl: {
      type: String,
      required: true
    }
  },
  computed: {
    getThumURL : function() {
      if (this.videoUrl.match(MATCH_URL_YOUTU)) {
        var videoId = this.videoUrl.split('/')[3];
        return THUMBNAIL_URL_YOUTUBE + videoId + THUMBNAIL_URL_YOUTUBE_IMAGE;
      } else if (this.videoUrl.match(MATCH_URL_YOUTUBE)) {
        var spritId = this.videoUrl.split('v=')[1];
        var videoId = spritId.split('&')[0];
        return THUMBNAIL_URL_YOUTUBE + videoId + THUMBNAIL_URL_YOUTUBE_IMAGE;
      } else if (this.videoUrl.match(MATCH_URL_TIKTOK)) {
        return require('../../img/tiktok.png');
      }
      return "";
    },
  }
}
</script>