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
import { INTERNAL_SERVER_ERROR } from './util'
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
      handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        }
      },
      immediate: true
    },
    $route () {
      this.$store.commit('error/setCode', null)
    }
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