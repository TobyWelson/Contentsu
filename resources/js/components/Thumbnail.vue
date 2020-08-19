<template>
    <div class="thumbnail"
      :class="{ 'niconico_thumbnail': isVideoTypeNiconico}">
      <img v-if="isVideoTypeNiconico" :src="getThumURL"
        class="back_image"
        onerror="this.src='../images/niconico.png'; this.removeAttribute('onerror'); this.removeAttribute('onload');"
        onload="this.removeAttribute('onerror'); this.removeAttribute('onload');"
        alt="alt here..."/>
      <v-img v-else :src="getThumURL"
        class="back_image"
        alt="alt here..."/>
      <div class="gard" />
    </div>
</template>

<script>
import { MATCH_URL_YOUTU, MATCH_URL_YOUTUBE, MATCH_URL_TIKTOK, MATCH_URL_NICOVIDEO, MATCH_URL_NICO, MATCH_URL_NICOSP } from '../util'
import { THUMBNAIL_URL_YOUTUBE, THUMBNAIL_URL_YOUTUBE_IMAGE, THUMBNAIL_URL_NICONICO } from '../util'

export default {
  props: {
    videoUrl: {
      type: String,
      required: true
    }
  },
  computed: {
    getThumURL : function() {
      // Youtube
      if (this.videoUrl.match(MATCH_URL_YOUTU)) {
        var videoId = this.videoUrl.split('/')[3];
        return THUMBNAIL_URL_YOUTUBE + videoId + THUMBNAIL_URL_YOUTUBE_IMAGE;
      } else if (this.videoUrl.match(MATCH_URL_YOUTUBE)) {
        var videoId = this.videoUrl.split('v=')[1].replace(/\&.*/g, '');
        return THUMBNAIL_URL_YOUTUBE + videoId + THUMBNAIL_URL_YOUTUBE_IMAGE;

      // TikTok
      } else if (this.videoUrl.match(MATCH_URL_TIKTOK)) {
        return require('../../img/tiktok.png');

      // NicoNico
      }
      else if (this.videoUrl.match(MATCH_URL_NICOSP)
        || this.videoUrl.match(MATCH_URL_NICOVIDEO)) {
        var spritId = this.videoUrl.split('watch/')[1].replace(/\?.*/g, '');
        var videoId = spritId.replace(/[^0-9]/g, '');
        return THUMBNAIL_URL_NICONICO + videoId + "/" + videoId
      } else if (this.videoUrl.match(MATCH_URL_NICO)) {
        var spritId = this.videoUrl.split('/')[3];
        var videoId = spritId.replace(/[^0-9]/g, '');
        return THUMBNAIL_URL_NICONICO + videoId + '/' + videoId

      }
      return "";
    },
    isVideoTypeNiconico : function() {
      if (this.videoUrl.match(MATCH_URL_NICOSP)
        || this.videoUrl.match(MATCH_URL_NICOVIDEO)
        || this.videoUrl.match(MATCH_URL_NICO)) {
        return true;
      } else {
        return false;
      }
    }
  }
}
</script>