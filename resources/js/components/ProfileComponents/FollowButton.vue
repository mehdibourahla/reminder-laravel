<template>
  <div>
    <button
      class="btn btn-primary"
      @click="followUser"
      v-text="buttonText"
    ></button>
  </div>
</template>

<script>
export default {
  props: ["userId"],
  mounted() {
    console.log("Component mounted.");
  },

  data: function () {
    fetch("/follow/" + this.userId).then((res) =>
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
    return {
      status: null,
    };
  },
  methods: {
    followUser() {
      axios
        .post("/follow/" + this.userId)
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
      if (this.status === null) {
        return "WAIT";
      } else {
        return this.status ? "Unfollow" : "Follow";
      }
    },
  },
};
</script>
