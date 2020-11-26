<template>
  <div>
    <div class="row d-flex">
      <message-component
        v-for="message in messages"
        :key="message.id"
        :user="user"
        :message="message"
        :query="query"
      ></message-component>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  mounted() {
    this.getData();
  },

  data: function () {
    return {
      messages: null,
      query: "/api/m/followedMsgs",
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
    show() {},
  },
};
</script>
