<template>
    <div class="thumb">
      <v-img :src="getThumURL"
             :class="{ 'niconico_thum': getVideoTypeNiconico,
                       'youtube_thum': getVideoTypeYoutube,
                      }"
              alt="alt here..."
              ref="thum_img"
              />
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
        var spritId = this.videoUrl.split('v=')[1];
        var videoId = spritId.split('&')[0];
        return THUMBNAIL_URL_YOUTUBE + videoId + THUMBNAIL_URL_YOUTUBE_IMAGE;

      // TikTok
      } else if (this.videoUrl.match(MATCH_URL_TIKTOK)) {
        return require('../../img/tiktok.png');

      // NicoNico
      } else if (this.videoUrl.match(MATCH_URL_NICOSP)) {
        var spritId = this.videoUrl.split('watch/')[1].replace(/\?.*/g, '');
        var videoId = spritId.replace(/[^0-9]/g, '');
        // return THUMBNAIL_URL_NICONICO + videoId + '/' + videoId
        const res = axios.get('http://ext.nicovideo.jp/api/getthumbinfo/' + spritId, {
          withCredentials: true,
          headers: {
            'Content-Type': 'application/json;charset=UTF-8',
          }
        })
        
        return ""


      } else if (this.videoUrl.match(MATCH_URL_NICOVIDEO)) {
        var spritId = this.videoUrl.split('watch/')[1].replace(/\?.*/g, '');
        var videoId = spritId.replace(/[^0-9]/g, '');
        return THUMBNAIL_URL_NICONICO + videoId + '/' + videoId


      } else if (this.videoUrl.match(MATCH_URL_NICO)) {
        var spritId = this.videoUrl.split('/')[3];
        var videoId = spritId.replace(/[^0-9]/g, '');
        return THUMBNAIL_URL_NICONICO + videoId + '/' + videoId
      }
      return "";
    },
    getVideoTypeNiconico : function() {
      if (this.videoUrl.match(MATCH_URL_NICOVIDEO) || this.videoUrl.match(MATCH_URL_NICO)) {
        return true;
      }
      return false;
    },
    getVideoTypeYoutube : function() {
      if (this.videoUrl.match(MATCH_URL_YOUTU) || this.videoUrl.match(MATCH_URL_YOUTUBE)) {
        return true;
      }
      return false;
    }
  }
}
</script>