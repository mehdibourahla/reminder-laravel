<template>
  <div>
    <div v-if="profileDetails !== null" class="row py-3">
      <div class="col-3">
        <loading-spinner v-show="show"></loading-spinner>
        <img
          v-show="!show"
          :src="profileDetails.picture"
          @load="loaded"
          class="img-thumbnail rounded-circle"
          style="width: 200px; height: 200px"
          alt=""
        />
      </div>
      <div class="col-6">
        <div class="d-flex align-items-center">
          <div class="h4 mr-4">{{ profileDetails.username }}</div>
          <follow-button :user-id="profileId"></follow-button>
        </div>
        <div class="d-flex pt-3">
          <div class="pr-5">
            <strong>{{ messagesCount }}</strong> messages
          </div>
          <div class="pr-5">
            <a class="text-dark" v-on:click="showFollowers">
              <strong>{{ followersCount }}</strong> followers
            </a>
          </div>
          <div class="pr-5">
            <a class="text-dark" v-on:click="showFollowing">
              <strong>{{ followingCount }}</strong> following
            </a>
          </div>
        </div>
        <div class="pt-3 font-weight-bold">{{ profileDetails.name }}</div>
        <div class="">{{ profileDetails.bio }}</div>
        <div>
          <a href="https://www.lipsum.com/">{{ profileDetails.url }}</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["profileId", "deleted"],
  created() {
    this.getDetails();
  },
  mounted() {
    this.$root.$on("updateFollows", () => {
      this.getFollowersCount();
      this.getFollowingCount();
    });
  },
  updated() {
    this.$emit("go");
  },
  data: function () {
    return {
      profileDetails: null,
      messagesCount: 0,
      followersCount: 0,
      followingCount: 0,
      loading: false,
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
    loaded() {
      setTimeout(() => {
        this.loading = false;
      }, 100);
    },
    reload() {
      this.getFollowersCount();
      this.getFollowingCount();
    },

    async getDetails() {
      try {
        this.loading = true;
        const res = await axios.get(
          "/api/profile/" + this.profileId + "/details"
        );
        this.profileDetails = res.data;
        this.profileDetails.picture = this.getImage();
        this.getMessagesCount();
        this.getFollowersCount();
        this.getFollowingCount();
        //
      } catch (error) {
        console.error(error);
      }
    },
    async getMessagesCount() {
      try {
        const res = await axios.get(
          "/api/profile/" + this.profileId + "/messagesCount"
        );
        this.messagesCount = res.data;
      } catch (error) {
        console.error(error);
      }
    },
    async getFollowersCount() {
      try {
        const res = await axios.get(
          "/api/profile/" + this.profileId + "/followersCount"
        );
        this.followersCount = res.data;
      } catch (error) {
        console.error(error);
      }
    },
    async getFollowingCount() {
      try {
        const res = await axios.get(
          "/api/profile/" + this.profileId + "/followingCount"
        );
        this.followingCount = res.data;
      } catch (error) {
        console.error(error);
      }
    },
    getImage() {
      const res = this.profileDetails.picture
        ? "/storage/" + this.profileDetails.picture
        : "/svg/user.svg";
      return res;
    },
    showFollowers: function () {
      this.$root.$emit("show-modal", "followers");
    },
    showFollowing: function () {
      this.$root.$emit("show-modal", "following");
    },
  },

  computed: {
    show() {
      return this.loading;
    },
  },
};
</script>
