<template>
  <div
    v-if="profileDetails !== null"
    class="d-flex align-items-center justify-content-between"
  >
    <img
      :src="getImage()"
      class="img-thumbnail rounded-circle"
      style="height: 200px"
      alt=""
    />
    <div class="pl-4 py-3">
      <div class="d-flex align-items-center">
        <div class="h4 mr-4">{{ profileDetails.username }}</div>
        <follow-button @pressed="reload" :user-id="profileId"></follow-button>
      </div>
      <div class="d-flex pt-3">
        <div class="pr-5">
          <strong>{{ messagesCount }}</strong> messages
        </div>
        <div class="pr-5">
          <strong>{{ followersCount }}</strong> followers
        </div>
        <div class="pr-5">
          <strong>{{ followingCount }}</strong> following
        </div>
      </div>
      <div class="pt-3 font-weight-bold">{{ profileDetails.name }}</div>
      <div class="w-75">{{ profileDetails.bio }}</div>
      <div>
        <a href="https://www.lipsum.com/">{{ profileDetails.url }}</a>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["profileId", "deleted"],
  mounted() {
    this.getData();
    this.getMessagesCount();
    this.getFollowersCount();
    this.getFollowingCount();
  },
  data: function () {
    return {
      profileDetails: null,
      messagesCount: 0,
      followersCount: 0,
      followingCount: 0,
    };
  },
  watch: {
    deleted: function () {
      if (this.deleted) {
        this.getMessagesCount();
        this.$emit("deleted", false);
      }
    },
  },
  methods: {
    reload() {
      this.getFollowersCount();
      this.getFollowingCount();
    },
    async getData() {
      try {
        const res = await axios.get("/profile/" + this.profileId + "/details");
        this.profileDetails = res.data;
      } catch (error) {
        console.error(error);
      }
    },
    async getMessagesCount() {
      try {
        const res = await axios.get(
          "/profile/" + this.profileId + "/messagesCount"
        );
        this.messagesCount = res.data;
      } catch (error) {
        console.error(error);
      }
    },
    async getFollowersCount() {
      try {
        const res = await axios.get(
          "/profile/" + this.profileId + "/followersCount"
        );
        this.followersCount = res.data;
      } catch (error) {
        console.error(error);
      }
    },
    async getFollowingCount() {
      try {
        const res = await axios.get(
          "/profile/" + this.profileId + "/followingCount"
        );
        this.followingCount = res.data;
      } catch (error) {
        console.error(error);
      }
    },
    getImage() {
      return this.profileDetails
        ? "/storage/" + this.profileDetails.picture
        : "";
    },
  },

  computed: {},
};
</script>
