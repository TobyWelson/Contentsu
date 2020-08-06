<template>
  <v-select
    class="mb-1"
    color="orange light-4"
    @input="setCategory"
    :items="categories"
    :value="category"
    @change="reset"
    label="カテゴリー検索"
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