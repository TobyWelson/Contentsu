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
          <i class="icon ion-md-heart"></i>12
        </button>
      </div>
    </RouterLink>
    <figure class="post__wrapper　post__username">
        <p>{{item.owner.name}}</p>
    </figure>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  }
}
</script>