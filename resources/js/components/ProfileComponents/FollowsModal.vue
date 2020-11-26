<template>
  <div
    class="modal fade"
    id="exampleModal"
    ref="modal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            {{ capitalizeFirstLetter(query) }}
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            @click="empty"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div v-for="(profile, index) in follows" :key="profile.id">
            <profile-preview
              :profile-id="profileId"
              :query="query"
              :profile="profile"
            ></profile-preview>
            <hr v-show="index !== follows.length - 1" />
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            @click="empty"
            class="btn btn-secondary"
            data-dismiss="modal"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  model: {
    event: "show-modal",
  },
  props: ["profileId"],
  created() {
    this.$root.$on("show-modal", (query) => {
      this.showModal(query);
    });
  },

  data: function () {
    return {
      follows: null,
      query: "",
    };
  },
  methods: {
    empty() {
      this.follows = null;
    },
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    showModal(query) {
      let element = this.$refs.modal;
      $(element).modal("show");
      this.query = query;
      this.getFollows();
    },
    async getFollows() {
      try {
        const res = await axios.get(
          "/api/profile/" + this.profileId + "/" + this.query
        );
        this.follows = res.data;
      } catch (error) {
        console.error(error);
      }
    },
  },

  computed: {},
};
</script>
