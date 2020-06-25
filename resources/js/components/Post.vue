<template>
<v-flex xs6 sm6 md4 lg4 xl4>
  <v-card flat link >
    <RouterLink
      class="post__overlay"
      :to="`/posts/${item.id}`">
      <YoutubeThum :videoUrl="item.url"></YoutubeThum>
      <v-card-title class="hover-orange over-text font-weight-bold">{{ item.title }}</v-card-title>
      <v-card-actions>
        <v-icon small>mdi-account-edit</v-icon>{{item.owner.name}}
        <v-spacer></v-spacer>
        <v-icon small>mdi-heart</v-icon>{{ item.likes_count }}&nbsp;&nbsp;
        <v-icon small>mdi-eye</v-icon>{{ item.likes_count }}
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