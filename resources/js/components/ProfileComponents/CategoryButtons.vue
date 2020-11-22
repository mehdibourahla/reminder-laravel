<template>
  <div class="row py-3">
    <div class="col-lg-12 col-md-8 col-sm-4">
      <button @click="showOwn" class="m-2 btn btn-primary">My Messages</button>
      <button @click="showLiked" class="m-2 btn btn-danger">
        Liked Messages
      </button>
      <button @click="showFavourite" class="m-2 btn btn-warning">
        Favourite Messages
      </button>
      <button @click="showHiden" class="m-2 btn btn-secondary">
        Hiden Messages
      </button>
    </div>
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
