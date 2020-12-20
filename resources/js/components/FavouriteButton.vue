<template>
  <div>
    <i
      @click="FavouriteMessage"
      v-bind:class="buttonStyle"
      class="fa-star fa-2x text-warning mx-2"
      style="cursor: pointer"
    >
    </i>
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
    async FavouriteMessage() {
      try {
        this.loading = true;
        this.reactions = _.xor(["fav"], this.reactions);
        this.$emit("statusChanged", this.reactions);
        await axios.post("/api/m/" + this.messageId + "/fav");
        this.loading = false;
      } catch (error) {
        console.error(error);
      }
    },
  },

  computed: {
    buttonStyle: function () {
      return {
        far: !this.reactions.includes("fav"),
        fas: this.reactions.includes("fav"),
      };
    },
  },
};
</script>
