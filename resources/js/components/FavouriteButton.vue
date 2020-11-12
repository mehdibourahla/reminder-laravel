<template>
  <div>
    <button
      class="btn btn-warning mr-3"
      @click="FavouriteMessage"
      v-text="buttonText"
    ></button>
  </div>
</template>

<script>
export default {
  props: ["message-id", "favourites"],
  mounted() {},

  data: function () {
    return {
      status: this.favourites.includes(this.messageId),
    };
  },
  methods: {
    FavouriteMessage() {
      console.log(this.messageId);
      axios
        .post("/react/" + this.messageId + "/fav")
        .then((response) => {
          this.status = !this.status;
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
      return this.status ? "Remove from Favourite" : "Add to Favourite";
    },
  },
};
</script>
