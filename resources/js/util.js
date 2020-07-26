
/**
 * レスポンスコード定義
 */
export const OK = 200
export const CREATED = 201
export const INTERNAL_SERVER_ERROR = 500
export const UNPROCESSABLE_ENTITY = 422
export const UNAUTHORIZED = 419
export const NOT_FOUND = 404

export const SUCCESS = true
export const FAILURE = false

/** URLマッチ条件 */
export const MATCH_URL_YOUTU = /youtu\.be/
export const MATCH_URL_YOUTUBE = /youtube\.com/
export const MATCH_URL_TIKTOK = /tiktok\.com/
export const MATCH_URL_NICOVIDEO = /nicovideo\.jp/
export const MATCH_URL_NICO = /nico\.ms/
export const MATCH_URL_NICOSP = /sp\.nicovideo\.jp/
/** 動画URL */
export const VIDEO_URL_YOUTUBE = 'http://www.youtube.com/embed/'
export const VIDEO_URL_TIKTOK = 'https://www.tiktok.com/embed/'
export const VIDEO_URL_NICONICO = 'https://embed.nicovideo.jp/watch/'
/** サムネイルURL */
export const THUMBNAIL_URL_YOUTUBE = 'http://img.youtube.com/vi/'
export const THUMBNAIL_URL_YOUTUBE_IMAGE = '/mqdefault.jpg'
export const THUMBNAIL_URL_NICONICO = 'https://nicovideo.cdn.nimg.jp/thumbnails/'


/**
 * クッキーの値を取得する
 * @param {String} searchKey 検索するキー
 * @returns {String} キーに対応する値
 */
export function getCookieValue (searchKey) {
    if (typeof searchKey === 'undefined') {
      return '';
    }
  
    let val = '';
    document.cookie.split(';').forEach(cookie => {
        const [key, value] = cookie.split('=');
        if (key === searchKey) {
            val = value;
            return val;
        }
      });
    
      return val;
}