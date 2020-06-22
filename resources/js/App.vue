<template>
    <v-app>
      <header>
        <Navbar />
      </header>
      <main>
        <v-container>
        <!-- <div class="container"> -->
          <Message />
          <RouterView />
        <!-- </div> -->
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

<style scoped>
.theme--light.v-application {
  height:100%;
  background: linear-gradient(#fdad00, #251b70);
}
@media screen and (max-width:600px){
  .container {
    padding: 5px;
}
}
</style>