import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'
import message from './message'
import post from './post'

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    post,
    message
  }
});

export default store;
