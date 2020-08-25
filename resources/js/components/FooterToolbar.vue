<template>
  <footer class="footer_toolbar hidden-md-and-up">
    <v-btn icon @click="onMenuClick"><v-icon small>mdi-magnify</v-icon></v-btn>
    <v-spacer></v-spacer>
    <v-btn icon @click="showPost" v-if="isLogin"><v-icon small>mdi-feather</v-icon></v-btn>
    <PostForm ref="post"/>
  </footer>
</template>

<script>
import PostForm from '../components/PostForm.vue'

export default {
  components: {
    PostForm,
  },
  methods: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    showPost() {
      this.$refs.post.isShowPostDialog = true;
    },
    onMenuClick() {
      this.$store.dispatch('screen/onMenuClick')
    }
  }
}
</script>