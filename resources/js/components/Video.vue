<template>
  <div class="back_view" :class="{ 'tiktok_back_view': isVideoTypeTiktok,}">
    <iframe
      class="video_view"
      :class="{ 'tiktok_view': isVideoTypeTiktok}"
      :src="`${getUrl}`"
      frameborder="0"
      allowfullscreen=""/>
  </div>
</template>

<script>
import { MATCH_URL_YOUTU, MATCH_URL_YOUTUBE, MATCH_URL_TIKTOK, MATCH_URL_TIKTOK_2, MATCH_URL_NICOVIDEO, MATCH_URL_NICO, MATCH_URL_NICO_SP } from '../util'
import { VIDEO_URL_YOUTUBE, VIDEO_URL_TIKTOK, VIDEO_URL_NICONICO } from '../util'

export default {
  props: {
    videoUrl: {
      type: String,
      required: true
    },
    isAuto: {
      type: Boolean,
      required: false
    }
  },
  computed: {
    getUrl : function() {
      var url = "";
      // Youtube
      if (this.videoUrl.match(MATCH_URL_YOUTU)) {
        url = VIDEO_URL_YOUTUBE + this.videoUrl.split('/')[3];
      } else if (this.videoUrl.match(MATCH_URL_YOUTUBE)) {
        var spritId = this.videoUrl.split('v=')[1];
        url = VIDEO_URL_YOUTUBE + spritId.split('&')[0];

      // TikTok
      } else if (this.videoUrl.match(MATCH_URL_TIKTOK)
        || this.videoUrl.match(MATCH_URL_TIKTOK_2)) {
        var spritId = this.videoUrl.split('video/')[1];
        url = VIDEO_URL_TIKTOK + spritId.split('?')[0];
      
      // NicoNico
      } else if (this.videoUrl.match(MATCH_URL_NICOVIDEO) || this.videoUrl.match(MATCH_URL_NICO_SP)) {
        var spritId = this.videoUrl.split('watch/')[1].replace(/\?.*/g, '');
        url = VIDEO_URL_NICONICO + spritId.split('?')[0];
      } else if (this.videoUrl.match(MATCH_URL_NICO)) {
        url = VIDEO_URL_NICONICO + this.videoUrl.split('/')[3];
      }
      // オート再生判定
      if(this.isAuto) {
        url = url + "?autoplay=1&mute=1";
      }

      return url;
    },
    isVideoTypeTiktok : function() {
      if (this.videoUrl.match(MATCH_URL_TIKTOK)
        || this.videoUrl.match(MATCH_URL_TIKTOK_2)) {
        return true;
      } else {
        return false;
      }
    }
  }
}
</script>