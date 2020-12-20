<template>
  <div>
    <i
      @click="hideMessage"
      v-bind:class="buttonStyle"
      class="far fa-2x text-secondary mx-2"
      style="cursor: pointer"
    ></i>
  </div>
</template>

<script>
var _ = require("lodash/array");
export default {
  props: ["messageId", "userReactions"],
  mounted() {
    this.reactions = this.userReactions;
  },

  data: function () {
    return {
      loading: false,
      reactions: [],
    };
  },

  methods: {
    async hideMessage() {
      try {
        this.loading = true;
        this.reactions = _.xor(["hide"], this.reactions);
        this.$emit("statusChanged", this.reactions);
        await axios.post("/api/m/" + this.messageId + "/hide");
        this.loading = false;
      } catch (error) {
        console.error(error);
      }
    },
  },

  computed: {
    buttonStyle: function () {
      return {
        "fa-eye-slash": !this.reactions.includes("hide"),
        "fa-eye": this.reactions.includes("hide"),
      };
    },
  },
};
</script>
