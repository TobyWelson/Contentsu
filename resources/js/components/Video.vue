<template>
  <div>
    <iframe
      class="youtube_view"
      :class="{ 'tiktok_view': getVideoTypeTiktok,
                'niconico_view': getVideoTypeNiconico,
              }"
      :src="`${getUrl}`"
      frameborder="0"
      allowfullscreen=""/>
  </div>
</template>

<script>
import { MATCH_URL_YOUTU, MATCH_URL_YOUTUBE, MATCH_URL_TIKTOK, MATCH_URL_NICOVIDEO, MATCH_URL_NICO, MATCH_URL_NICOSP } from '../util'
import { VIDEO_URL_YOUTUBE, VIDEO_URL_TIKTOK, VIDEO_URL_NICONICO } from '../util'

export default {
  props: {
    videoUrl: {
      type: String,
      required: true
    }
  },
  computed: {
    getUrl : function() {
      // Youtube
      if (this.videoUrl.match(MATCH_URL_YOUTU)) {
        return VIDEO_URL_YOUTUBE + this.videoUrl.split('/')[3];
      } else if (this.videoUrl.match(MATCH_URL_YOUTUBE)) {
        var spritId = this.videoUrl.split('v=')[1];
        return VIDEO_URL_YOUTUBE + spritId.split('&')[0];

      // TikTok
      } else if (this.videoUrl.match(MATCH_URL_TIKTOK)) {
        var spritId = this.videoUrl.split('video/')[1];
        return VIDEO_URL_TIKTOK + spritId.split('?')[0];
      
      // NicoNico
      } else if (this.videoUrl.match(MATCH_URL_NICOVIDEO) || this.videoUrl.match(MATCH_URL_NICOSP)) {
        var spritId = this.videoUrl.split('watch/')[1].replace(/\?.*/g, '');
        return VIDEO_URL_NICONICO + spritId.split('?')[0];
      } else if (this.videoUrl.match(MATCH_URL_NICO)) {
        return VIDEO_URL_NICONICO + this.videoUrl.split('/')[3];
      }
      return "";
    },
    getVideoTypeTiktok : function() {
      if (this.videoUrl.match(MATCH_URL_TIKTOK)) {
        return true;
      }
      return false;
    },
    getVideoTypeNiconico : function() {
      if (this.videoUrl.match(MATCH_URL_NICOVIDEO) || this.videoUrl.match(MATCH_URL_NICO)) {
        return true;
      }
      return false;
    }
  }
}
</script>