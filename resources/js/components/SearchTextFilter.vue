<template>
  <v-text-field
    class="mb-1"
    color="orange light-4"
    @input="setText"
    @change="reset"
    label="フリーワード検索"
    flat
    solo
    hide-details>
  </v-text-field>
</template>

<script>
export default {

  methods: {
    setText(val) {
      this.$store.commit('filter/setText', val);
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