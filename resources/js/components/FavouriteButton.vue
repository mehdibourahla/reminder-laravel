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
  props: ["message-id", "isFavourite"],
  mounted() {
    this.getFavCount();
  },

  data: function () {
    let favouriteMsg = this.isFavourite;
    return {
      status: favouriteMsg,
      favCount: 0,
    };
  },
  methods: {
    async getFavCount() {
      try {
        const res = await axios.get("/api/m/" + this.messageId + "/fav");
        this.favCount = res.data.length;
      } catch (error) {
        console.error(error);
      }
    },
    FavouriteMessage() {
      axios
        .post("/api/m/" + this.messageId + "/fav")
        .then((response) => {
          this.status = !this.status;
          this.getFavCount();
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
