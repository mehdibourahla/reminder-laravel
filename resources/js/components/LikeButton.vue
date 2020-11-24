<template>
  <div>
    <button
      class="btn btn-danger mr-3"
      @click="likeMessage"
      v-text="buttonText + ' (' + likesCount + ')'"
    ></button>
  </div>
</template>

<script>
export default {
  props: ["message-id", "isLiked", "likesCount"],
  mounted() {},

  data: function () {
    let likedMsg = this.isLiked;
    return {
      status: likedMsg,
    };
  },
  methods: {
    likeMessage() {
      axios
        .post("/react/" + this.messageId + "/like")
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
      return this.status ? "Unlike" : "Like";
    },
  },
};
</script>
