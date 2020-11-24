<template>
  <div>
    <button
      class="btn btn-warning mr-3 font-weight-bold"
      @click="FavouriteMessage"
      v-text="buttonText + ' (' + favCount + ')'"
    ></button>
  </div>
</template>

<script>
export default {
  props: ["message-id", "isFavourite", "favCount"],
  mounted() {},

  data: function () {
    let favouriteMsg = this.isFavourite;
    return {
      status: favouriteMsg,
    };
  },
  methods: {
    FavouriteMessage() {
      axios
        .post("/react/" + this.messageId + "/fav")
        .then((response) => {
          this.status = !this.status;
          this.$emit("statusChanged", this.status);
        })
        .catch((errors) => {
          if (errors.response.status === 401) {
            window.location = "/login";
          }
        });
    },
  },

  computed: {
    buttonText() {
      return this.status ? "Remove from Favourite" : "Favourite";
    },
  },
};
</script>
