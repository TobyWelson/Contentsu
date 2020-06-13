<template>
  <div class="post">
    <figure class="post__wrapper">
      <img
        class="post__image"
        :src="xxxxxxxxxxxxxx"
        :alt="`Photo by ${item.owner.name}`"
      >
    </figure>
    <RouterLink
      class="post__overlay"
      :to="`/posts/${item.id}`"
      :title="`View the post by ${item.owner.name}`"
    >
      <div class="post__controls">
        <button
          class="post__action post__action--like"
          title="Like post"
        >
          <i class="icon ion-md-heart"></i>12
        </button>
      </div>
      <div class="photo__username">
        {{ item.owner.name }}
      </div>
    </RouterLink>
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