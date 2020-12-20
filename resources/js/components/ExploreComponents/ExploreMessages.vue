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
    document.addEventListener("scroll", (e) => {
      if (
        document.documentElement.scrollTop +
          document.documentElement.clientHeight >=
        document.documentElement.scrollHeight
      ) {
        this.page++;
        this.getData();
      }
    });
  },

  data: function () {
    return {
      messages: [],
      page: 1,
    };
  },
  methods: {
    async getData() {
      try {
        let res = await axios.get("/api/m/followedMsgs?page=" + this.page, {
          params: {
            user: this.$userId,
          },
        });
        res.data.forEach((element) => {
          this.messages.push(element);
        });
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
