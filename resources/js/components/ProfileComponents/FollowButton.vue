<template>
  <div>
    <div>
      <a class="btn btn-primary" @click="followUser" v-text="buttonText"> </a>
    </div>
  </div>
</template>

<script>
export default {
  props: ["userId"],
  mounted() {
    this.isFollowed();
    this.$root.$on("updateFollows", () => {
      this.isFollowed();
    });
  },

  data: function () {
    return {
      status: null,
    };
  },
  methods: {
    isFollowed() {
      fetch("/api/profile/" + this.userId + "/follow").then((res) =>
        res
          .json()
          .then((data) => {
            this.status = data;
          })
          .catch((errors) => {
            if (errors.response.status === 401) {
              window.location = "/login";
            }
          })
      );
    },
    followUser() {
      axios
        .post("/api/profile/" + this.userId + "/follow")
        .then((response) => {
          this.status = !this.status;
          this.$root.$emit("updateFollows");
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
      return this.status ? "Unfollow" : "Follow";
    },
    show() {
      return this.status === null;
    },
  },
};
</script>
