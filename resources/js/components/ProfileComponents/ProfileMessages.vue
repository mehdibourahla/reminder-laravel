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
        :user="user"
        :query="query"
        :message="message"
      ></message-component>
    </div>
  </div>
</template>

<script>
export default {
  props: ["profileId", "user"],
  mounted() {
    this.getData();
  },

  data: function () {
    return {
      messages: null,
      query: "/profile/" + this.profileId + "/" + "m",
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
    filter(value) {
      this.messages = null;
      this.query = "/profile/" + this.profileId + "/" + value;

      this.getData();
    },
    async getData() {
      let res = await fetch(this.query);
      if (!res.ok) {
        console.error("You shall not pass ! ");
      } else {
        this.messages = await res.json();
        this.messages.sort(this.compare);
      }
    },
  },
  computed: {
    show() {
      return this.user ? JSON.parse(this.user).id == this.profileId : false;
    },
  },
};
</script>
