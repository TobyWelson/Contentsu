<template>
<v-flex xs6 sm6 md4 lg4 xl4　px-2 py-2>
  <v-card hover>
    <RouterLink
      class="post__overlay"
      :to="`/posts/${item.id}`">
      <YoutubeThum :videoUrl="item.url"></YoutubeThum>
      <v-card-title>{{ item.title }}</v-card-title>
    </RouterLink>
    <v-card-actions>
      <v-card-text>{{item.owner.name}}</v-card-text>
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon>mdi-heart</v-icon>
      </v-btn>
    </v-card-actions>
    <div class="post__viewcount">
      {{ item.view }}
    </div>
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
  }
}
</script>

<style scoped>
.v-card__actions{padding: 2px;}
.v-card__subtitle, .v-card__text, .v-card__title {padding: 10px;}
a {text-decoration: none}
.v-application a {color: #222;}

@media screen and (max-width:600px){
  .v-application .py-2 {
      padding-top: 4px!important;
      padding-bottom: 4px!important;
  }
  .v-application .px-2 {
      padding-right: 4px!important;
      padding-left: 4px!important;
  }
}
</style>