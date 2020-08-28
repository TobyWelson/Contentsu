<template>
  <v-select
    class="pa-2"
    color="orange light-4"
    v-model="category"
    :items="categories"
    item-text="categoryName"
    item-value="id"
    @change="reset"
    flat
    solo
    hide-details>
  </v-select>
</template>

<script>
import { mapState } from 'vuex'
export default {
  computed: {
    ...mapState({
      categories: state => state.filter.categories
    }),
    category: {
      get: function () { return this.$store.getters['filter/category']; },
      set: function (val) { this.$store.commit('filter/setCategory', val); },
    }
  },
  methods: {
    reset() {
      window.scrollTo({
        top: 0,
        behavior: "instant"
      });
      this.$emit('reset');
    }
  }
}
</script>