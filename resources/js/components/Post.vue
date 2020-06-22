<template>
  <div class="post">
    <RouterLink
      class="post__overlay"
      :to="`/posts/${item.id}`"
      :title="`コメントコメントコメント`"
    >
      <div class="post__title">
        {{ item.title }}
      </div>
      <div class="post__controls">
      </div>
      <YoutubeThum :videoUrl="item.url"></YoutubeThum>
    </RouterLink>
    <i class="icon ion-md-heart"></i>{{ item.likes_count }}
    <div class="post__remarks">
      閲覧者数: {{ item.view_count }}<br/>
      転載者: {{item.owner.name}}
    </div>
  </div>
</template>

<script>
import YoutubeThum from './YoutubeThum.vue'

export default {
  components: {
    YoutubeThum
  },
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  methods : {
    like () {
      this.$emit('like', {
        id: this.item.id,
        liked: this.item.liked_by_user
      })
    }
  }
}
</script>