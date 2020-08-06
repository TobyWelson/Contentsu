<template>
<v-flex xs12 sm6 md4 lg4 xl4 class="post">
  <v-card class="mx-1 my-1 post_hover" outlined>
    <RouterLink class="post__overlay" :to="`/posts/${item.id}`">
      <Thumbnail :videoUrl="item.url"></Thumbnail>
      <v-card-title class="title_over_text font-weight-bold">{{ item.title }}</v-card-title>
      <v-card-subtitle class="title_over_text">{{ item.category }}</v-card-subtitle>
      <div class="titlegard" />
      <v-divider></v-divider>
      <v-card-actions>
        <v-icon small>mdi-account</v-icon><span class="name_over_text text-caption">{{item.owner.name}}</span>
        <v-spacer></v-spacer>
        <v-icon small color="#e4406f">mdi-heart</v-icon><span class="mr-1">{{ item.likes_count }}</span>
        <v-icon small color="blue">mdi-eye</v-icon><span class="">{{ item.view_count }}</span>
      </v-card-actions>
    </RouterLink>
  </v-card>
</v-flex>
</template>

<script>
import Thumbnail from './Thumbnail.vue'

export default {
  components: {
    Thumbnail
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
  },
}
</script>