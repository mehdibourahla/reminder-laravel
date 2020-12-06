<template>
  <div class="container">
    <profile-details
      :profile-id="profileId"
      @deleted="deleteMsg"
      :deleted="deleted"
      @go="loaded"
    >
    </profile-details>
    <follows-modal :profile-id="profileId" :user="user"></follows-modal>
    <profile-messages
      v-if="show"
      :user="user"
      :profile-id="profileId"
      @deleted="deleteMsg"
    ></profile-messages>
    <loading-spinner
      v-else
      v-bind:style="{ width: '200px', margin: 0 + ' auto' }"
    ></loading-spinner>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["profileId", "user"],
  mounted() {
    // this.getNotifications();
  },

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
