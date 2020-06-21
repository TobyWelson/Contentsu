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
        <button
          class="post__action post__action--like"
          title="Like post"
        >
          <i class="icon ion-md-heart"></i>
        </button>
      </div>
      <YoutubeThum :videoUrl="item.url"></YoutubeThum>
    </RouterLink>
    <div class="post__viewcount">
      {{ item.view }}
    </div>
    <figure class="post__wrapper　post__username">
        <p>{{item.owner.name}}</p>
    </figure>
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
  }
}
</script>