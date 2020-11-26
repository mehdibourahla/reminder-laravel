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
  props: ["messageId", "isLiked"],
  mounted() {
    this.getLikeCount();
  },

  data: function () {
    let likedMsg = this.isLiked;
    return {
      status: likedMsg,
      likesCount: 0,
    };
  },
  methods: {
    async getLikeCount() {
      try {
        const res = await axios.get("/api/m/" + this.messageId + "/likes");
        this.likesCount = res.data.length;
      } catch (error) {
        console.error(error);
      }
    },
    likeMessage() {
      axios
        .post("/api/m/" + this.messageId + "/like")
        .then((response) => {
          this.status = !this.status;
          this.getLikeCount();
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
