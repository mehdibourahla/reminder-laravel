<template>
  <div v-if="show" class="d-flex align-items-center justify-content-between">
    <a class="text-dark" :href="'/profile/' + profile.id">
      <div class="d-flex align-items-center">
        <img
          :src="getImgUrl(profile)"
          alt="profile-img"
          class="img-thumbnail rounded-circle"
          style="width: 50px"
        />
        <div class="ml-2">
          <span
            ><strong>{{ profile.username }}</strong></span
          >
          <p class="text-muted p-0 m-0">{{ profile.name }}</p>
        </div>
      </div>
    </a>

    <button v-if="showButton" @click="removeFollower" class="btn btn-secondary">
      Remove
    </button>
    <follow-button v-else :user-id="profile.id"></follow-button>
  </div>
</template>

<script>
export default {
  props: ["profile", "query", "profileId"],

  mounted() {},

  data: function () {
    return {
      status: true,
    };
  },
  methods: {
    getImgUrl(profile) {
      return profile.picture ? "/storage/" + profile.picture : "/svg/user.svg";
    },
    async removeFollower() {
      try {
        const res = await axios.get(
          "/api/profile/" + this.profile.id + "/removeFollower"
        );
        console.log(this.profile.id);
        this.status = !this.status;
        this.$root.$emit("updateFollows");
      } catch (errors) {
        console.error(errors);
      }
    },
  },

  computed: {
    show() {
      return this.status;
    },
    showButton() {
      return this.$userId === this.profileId && this.query == "followers";
    },
  },
};
</script>
