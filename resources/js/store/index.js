import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'
import message from './message'
import post from './post'
import filter from './filter'
import screen from './screen'
import comments from './comments'

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    post,
    filter,
    screen,
    message,
    comments
  }
});

export default store;
