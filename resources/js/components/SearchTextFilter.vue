<template>
  <div class="comment_submit">
    <v-text-field
      class="pa-2"
      color="orange light-4"
      v-model="inputKeyword"
      @input="setText"
      label="例)タイトル名"
      flat
      solo
      hide-details>
    </v-text-field>
    <div class="submit">
      <v-btn icon @click="reset" color="warning"><v-icon>mdi-magnify</v-icon></v-btn>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    keyword: {
      type: String
    }
  },
  data() {
    return {
      inputKeyword: this.keyword
    }
  },
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