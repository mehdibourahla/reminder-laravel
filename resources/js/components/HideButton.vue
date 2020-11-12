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
  props: ["message-id", "hides", "parentStatus"],
  mounted() {},

  data: function () {
    return {
      status: this.hides.includes(this.messageId),
    };
  },
  methods: {
    hideMessage() {
      this.$emit("statusChanged", this.status);
      axios
        .post("/react/" + this.messageId + "/hide")
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
      return this.status ? "Show" : "Hide";
    },
  },
};
</script>
