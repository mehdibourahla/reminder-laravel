<template>
  <div>
    <i
      @click="likeMessage"
      v-bind:class="buttonStyle"
      class="fa-heart fa-2x text-danger mx-2"
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
    async likeMessage() {
      try {
        this.loading = true;
        this.reactions = _.xor(["like"], this.reactions);
        this.$emit("statusChanged", this.reactions);
        await axios.post("/api/m/" + this.messageId + "/like");
        this.loading = false;
      } catch (error) {
        console.error(error);
      }
    },
  },

  computed: {
    buttonStyle: function () {
      return {
        far: !this.reactions.includes("like"),
        fas: this.reactions.includes("like"),
      };
    },
  },
};
</script>
