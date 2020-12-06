<template>
  <div class="dropdown">
    <a
      class="pt-2 pr-3"
      type="button"
      id="dropdownNotifictionButton"
      data-toggle="dropdown"
      @click="getNotifications"
    >
      <span class="text-dark"> Notifications </span>
    </a>
    <div
      class="dropdown-menu justify-content-center"
      aria-labelledby="dropdownNotifictionButton"
      style="width: 300px"
    >
      <h6 class="pl-2">Recent Notifications</h6>
      <ul class="list-group">
        <li
          v-for="notification in notifications"
          :key="notification.id"
          v-bind:class="classObject(notification.type)"
          class="dropdown-item list-group-item px-2"
        >
          <a
            class="p-0 text-dark"
            :href="notification.redirection"
            style="white-space: normal; text-decoration: none"
          >
            <div class="d-flex align-items-center">
              <img
                :src="getImg(notification)"
                alt="profile-pic"
                class="img-thumbnail rounded-circle"
                style="width: 50px"
              />
              <div>
                <strong>{{ notification.user.username }} </strong
                >{{ notification.message }}
              </div>
            </div>
            <div>
              <small class="float-right">{{
                notification.creation_date
              }}</small>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: [""],
  mounted() {},

  data: function () {
    return {
      notifications: [],
    };
  },
  methods: {
    classObject(type) {
      return {
        "list-group-item-success": type === 3,
        "list-group-item-danger": type === 1,
        "list-group-item-warning": type === 2,
        "list-group-item-info": type === 4,
      };
    },
    getImg(notification) {
      const res = notification.user.profile.picture
        ? "/storage/" + notification.user.profile.picture
        : "/svg/user.svg";
      return res;
    },
    async getNotifications() {
      this.notifications = [];
      try {
        let res = await axios.get("/api/notification/getNotifications");
        res.data.data.forEach((notification) => {
          let id = notification.id;
          let user = null;
          let creation_date = notification.created_at;
          let redirection = "";
          let type = -1;
          let message = "";
          if (notification.type.includes("Reaction")) {
            user = notification.data.user;
            type = 1;
            message = "reacted to your message.";
            redirection = "/m/" + notification.data.message.id;
          } else if (notification.type.includes("Post")) {
            user = notification.data.author;
            type = 2;
            message = "posted a new message.";
            redirection = "/m/" + notification.data.message.id;
          } else if (notification.type.includes("Follower")) {
            user = notification.data.follower;
            type = 3;
            message = "started following you.";
            redirection = "/profile/" + notification.data.follower.id;
          } else if (notification.type.includes("Reminder")) {
            type = 4;
            user = notification.data.author;
            message = notification.data.message.caption;
            redirection = "/m/" + notification.data.message.id;
          }
          this.notifications.push({
            id,
            user,
            creation_date,
            redirection,
            type,
            message,
          });
        });
        console.log(this.notifications);
      } catch (error) {
        console.error(error);
      }
    },
  },

  computed: {},
};
</script>
