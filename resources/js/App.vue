<template>
    <v-app>
      <v-overlay :value="isLoading" color="white" opacity="1">
        <v-progress-circular indeterminate :size="80" :width="7" color="deep-orange lighten-2"></v-progress-circular>
      </v-overlay>
      <header>
        <Navbar />
      </header>
      <main>
        <v-container class="pb-0">
          <Message />
          <RouterView />
        </v-container>
      </main>
      <Footer />
    </v-app>
</template>

<script>
import Message from './components/Message.vue'
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from './util'
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'

export default {
  components: {
    Message,
    Navbar,
    Footer
  },
  computed: {
    errorCode () {
      return this.$store.state.error.code
    },
    isLoading () {
      return this.$store.getters['screen/loading']
    }
  },
  watch: {
    errorCode: {
      async handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        } else if (val === UNAUTHORIZED) {
          await axios.get('/api/refresh-token')
          this.$store.commit('auth/setUser', null)
          this.$router.push('/login')
        } else if (val === NOT_FOUND) {
          this.$router.push('/not-found')
        }
      }
    },
    immediate: true
  }
}
</script>