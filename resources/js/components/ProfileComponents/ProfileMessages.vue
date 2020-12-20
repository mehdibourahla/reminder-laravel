<template>
  <div>
    <div v-if="show">
      <category-buttons @queryChanged="filter"></category-buttons>
    </div>
    <div class="row d-flex">
      <message-component
        v-for="message in messages"
        v-on="$listeners"
        :key="message.id"
        :query="query"
        :message="message"
      ></message-component>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["profileId"],
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
      query: "/api/profile/m",
      page: 1,
    };
  },
  methods: {
    filter(value) {
      this.messages = [];
      this.page = 1;
      this.query = "/api/profile/" + value;
      this.getData();
    },
    async getData() {
      try {
        let res = await axios.get(this.query + "?page=" + this.page, {
          params: {
            profile: this.profileId,
          },
        });
        res.data.forEach((element) => {
          this.messages.push(element);
        });
      } catch (error) {
        console.error(error);
      }
    },
  },
  computed: {
    show() {
      return this.$userId ? this.$userId == this.profileId : false;
    },
  },
};
</script>
