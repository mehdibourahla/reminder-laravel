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
    <button @click="removeFollower" class="btn btn-secondary">Remove</button>
  </div>
</template>

<script>
export default {
  props: ["profile"],
  mounted() {},

  data: function () {
    return {
      status: true,
    };
  },
  methods: {
    getImgUrl(profile) {
      return "/storage/" + profile.picture;
    },
    async removeFollower() {
      try {
        const res = await axios.post(
          "/api/profile/" + this.profile.id + "/follow"
        );
        this.status = !this.status;
        this.$root.$emit("remove");
      } catch (errors) {
        console.error(errors);
      }
    },
  },

  computed: {
    show() {
      return this.status;
    },
  },
};
</script>
