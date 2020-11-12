<template>
  <div>
    <button
      class="btn btn-danger mr-3"
      @click="likeMessage"
      v-text="buttonText"
    ></button>
  </div>
</template>

<script>
export default {
  props: ["message-id", "likes"],
  mounted() {},

  data: function () {
    return {
      status: this.likes.includes(this.messageId),
    };
  },
  methods: {
    likeMessage() {
      axios
        .post("/react/" + this.messageId + "/like")
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
      return this.status ? "Unlike" : "Like";
    },
  },
};
</script>
