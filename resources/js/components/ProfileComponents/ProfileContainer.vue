<template>
  <div class="container">
    <profile-details
      :profile-id="profileId"
      @deleted="deleteMsg"
      :deleted="deleted"
      @go="loaded"
    >
    </profile-details>
    <follows-modal :profile-id="profileId"></follows-modal>
    <profile-messages
      v-show="show"
      :user="user"
      :profile-id="profileId"
      @deleted="deleteMsg"
    ></profile-messages>
  </div>
</template>

<script>
export default {
  props: ["profileId", "user"],
  mounted() {},

  data: function () {
    return {
      deleted: false,
      show: false,
    };
  },
  methods: {
    loaded() {
      this.show = true;
    },
    deleteMsg(value) {
      this.deleted = value;
    },
  },

  computed: {},
};
</script>
