<template>
<v-flex xs6 sm6 md4 lg3 xl3>
  <v-card class="mx-1 my-1 post-hover">
    <RouterLink
      class="post__overlay"
      :to="`/posts/${item.id}`">
      <YoutubeThum :videoUrl="item.url"></YoutubeThum>
      <v-card-title class="title-over-text font-weight-bold text-body-2 text-sm-h5 text-md-h6 text-lg-h6 text-xl-h6">{{ item.title }}</v-card-title>
      <v-card-actions>
        <v-icon small>mdi-account</v-icon><span class="name-over-text text-caption text-sm-subtitle-1 text-md-subtitle-1 text-lg-subtitle-1 text-xl-subtitle-1">{{item.owner.name}}</span>
        <v-spacer></v-spacer>
        <v-icon small>mdi-heart</v-icon><span class="mr-1 text-subtitle-2 text-sm-subtitle-1 text-md-subtitle-1 text-lg-subtitle-1 text-xl-subtitle-1">{{ item.likes_count }}</span>
        <v-icon small>mdi-eye</v-icon><span class="text-subtitle-2 text-sm-subtitle-1 text-md-subtitle-1 text-lg-subtitle-1 text-xl-subtitle-1">{{ item.view_count }}</span>
      </v-card-actions>
    </RouterLink>
  </v-card>
</v-flex>
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