<template>
  <div
    v-if="notification !== null"
    class="fixed-bottom"
    style="padding-left: 20px"
  >
    <div
      id="alert"
      class="alert alert-primary"
      role="alert"
      style="width: 250px; padding: 10px 5px"
    >
      <div class="d-flex justify-content-between align-items-start">
        <a
          class="d-flex align-items-center"
          style="text-decoration: none"
          :href="notification.redirection"
        >
          <img
            :src="getImg(notification)"
            alt="profile-pic"
            class="img-thumbnail rounded-circle"
            style="width: 50px"
          />
          <div class="text-dark">
            <strong>{{ notification.user.username }} </strong
            >{{ notification.message }}
          </div>
        </a>
        <button class="btn btn-link" href="" @click="hide">X</button>
      </div>
      <small>{{ notification.creation_date }}</small>
    </div>
  </div>
</template>

<script>
export default {
  props: [""],
  created() {},
  mounted() {
    this.subscribe();
  },

  data: function () {
    return {
      notification: null,
    };
  },
  watch: {
    notification: function (val) {
      if (val !== null) {
        setTimeout(() => {
          this.notification = null;
        }, 10000);
      }
    },
  },
  methods: {
    hide() {
      this.notification = null;
    },
    getImg(notification) {
      const res = notification.user.profile.picture
        ? "/storage/" + notification.user.profile.picture
        : "/svg/user.svg";
      return res;
    },
    subscribe() {
      var pusher = new Pusher("881559dd5578864ceeb0", {
        cluster: "eu",
      });
      var channel = pusher.subscribe("channel-" + this.$userId);
      channel.bind(
        "notification-push",
        async function (data) {
          try {
            let res = await axios.get("/api/notification/getLastNotification");
            this.notification = res.data;
          } catch (error) {
            console.error(error);
          }
        },
        this
      );
    },
  },

  computed: {},
};
</script>
