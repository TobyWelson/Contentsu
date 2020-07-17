<template>

      <v-select
        light
        color="orange light-4"
        class="mb-1"
        @input="setCategory"
        :items="categories"
        :value="category"
        @change="reset"
        label="カテゴリー検索"
        outlined
        flat
        solo-inverted
        hide-details
      ></v-select>
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
      this.$emit('reset');
    }
  }
}
</script>