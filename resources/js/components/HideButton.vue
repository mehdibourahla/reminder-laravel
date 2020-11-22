<template>
  <div>
    <button
      class="btn btn-secondary mr-3"
      @click="hideMessage"
      v-text="buttonText"
    ></button>
  </div>
</template>

<script>
export default {
  props: ["messageId", "isHidden"],
  mounted() {},

  data: function () {
    let hiddenMsg = this.isHidden;
    return {
      status: hiddenMsg,
    };
  },
  methods: {
    hideMessage() {
      axios
        .post("/react/" + this.messageId + "/hide")
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
      return this.status ? "Show" : "Hide";
    },
  },
};
</script>
