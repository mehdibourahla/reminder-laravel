<template>
  <div class="container">
    <profile-details
      :profile-id="profileId"
      @deleted="deleteMsg"
      :deleted="deleted"
    >
    </profile-details>
    <profile-messages
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
    };
  },
  methods: {
    deleteMsg(value) {
      this.deleted = value;
    },
  },

  computed: {},
};
</script>
