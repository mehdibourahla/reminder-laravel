<template>
  <div>
    <div class="dropdown btn-group" ref="myDropDown">
      <a
        class="pt-2 pr-3 dropdown-toggle"
        type="button"
        id="dropdownNotifictionButton"
        data-toggle="dropdown"
        @click="getData"
      >
        <span class="text-dark"> Notifications </span>
      </a>
      <div
        ref="scrollable"
        class="dropdown-menu justify-content-center scrollable-menu"
        aria-labelledby="dropdownNotifictionButton"
        style="width: 300px"
      >
        <div class="px-2 d-flex justify-content-between align-items-center">
          <h6 style="margin: 0">Recent Notifications</h6>
          <small
            @click="markAllAsRead"
            class="text-info"
            style="cursor: pointer"
            >Mark all as Read</small
          >
        </div>
        <ul class="list-group">
          <li
            v-for="notification in notifications"
            :key="notification.id"
            v-bind:class="classObject(notification.isRead)"
            class="dropdown-item list-group-item px-2"
            @click="markAsRead(notification.id)"
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
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: [""],
  mounted() {
    const listElm = this.$refs.scrollable;
    listElm.addEventListener("scroll", (e) => {
      if (listElm.scrollTop + listElm.clientHeight >= listElm.scrollHeight) {
        this.page++;
        this.getNotifications();
      }
    });
  },

  data: function () {
    return {
      notifications: [],
      page: 1,
    };
  },
  methods: {
    classObject(isRead) {
      return {
        "list-group-item-info": !isRead,
      };
    },
    getImg(notification) {
      const res = notification.user.profile.picture
        ? "/storage/" + notification.user.profile.picture
        : "/svg/user.svg";
      return res;
    },
    getData() {
      this.notifications = [];
      this.page = 1;
      this.getNotifications();
    },
    async getNotifications() {
      try {
        let res = await axios.get(
          "/api/notification/getNotifications?page=" + this.page
        );
        res.data.forEach((element) => {
          this.notifications.push(element);
        });
      } catch (error) {
        console.error(error);
      }
    },
    async markAsRead(notificationId) {
      try {
        await axios.get("/api/notification/" + notificationId + "/markAsRead");
      } catch (error) {
        console.error(error);
      }
    },
    async markAllAsRead() {
      try {
        await axios.get("/api/notification/markAllAsRead");
      } catch (error) {
        console.error(error);
      }
    },
  },

  computed: {},
};
</script>
