<template>
  <div class="d-flex justify-content-around" style="margin: 10px 100px">
    <button @click="showOwn" class="btn btn-primary mx-2">My Messages</button>
    <button @click="showLiked" class="btn btn-danger mx-2">
      Liked Messages
    </button>
    <button @click="showFavourite" class="btn btn-warning mx-2">
      Favourite Messages
    </button>
    <button @click="showHiden" class="btn btn-secondary">Hiden Messages</button>
  </div>
</template>

<script>
export default {
  props: [],
  mounted() {},

  data: function () {
    return {};
  },
  methods: {
    showOwn() {
      this.$emit("queryChanged", "m");
    },
    showLiked() {
      this.$emit("queryChanged", "likes");
    },
    showFavourite() {
      this.$emit("queryChanged", "fav");
    },
    showHiden() {
      this.$emit("queryChanged", "hidden");
    },
  },

  computed: {},
};
</script>
