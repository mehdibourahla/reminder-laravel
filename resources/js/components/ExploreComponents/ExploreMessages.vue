<template>
  <div>
    <div class="row d-flex">
      <message-component
        v-for="message in messages"
        :key="message.id"
        :message="message"
      ></message-component>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: [""],
  mounted() {
    this.getData();
  },

  data: function () {
    return {
      messages: null,
    };
  },
  methods: {
    compare(a, b) {
      if (a.created_at < b.created_at) {
        return 1;
      }
      if (a.created_at > b.created_at) {
        return -1;
      }
      return 0;
    },

    async getData() {
      try {
        let res = await axios.get("/api/m/followedMsgs", {
          params: {
            user: this.$userId,
          },
        });
        this.messages = res.data;
        this.messages.sort(this.compare);
      } catch (error) {
        console.error("You shall not pass ! ");
      }
    },
  },

  computed: {
    show() {},
  },
};
</script>
