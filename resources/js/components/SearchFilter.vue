<template>
  <v-select
    class="pa-2"
    color="orange light-4"
    @input="setCategory"
    :items="categories"
    :value="category"
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
        category: state => state.filter.category,
        categories: state => state.filter.categories
    }),
  },
  methods: {
    setCategory(val) {
      this.$store.commit('filter/setCategory', val);
    },
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