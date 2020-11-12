<template>
  <div>
    <div v-if="show" class="card">
      <div class="card-body">
        <div>
          <a :href="'/profile/' + msg.user_id">
            <span class="text-dark">{{ msg.user.username }} </span>
          </a>
        </div>
        <a :href="'/m/' + msg.id">
          <h5 class="card-title">{{ msg.caption }}</h5>
        </a>
        <h6 class="card-subtitle mb-2 text-muted">{{ msg.created_at }}</h6>
        <p class="card-text">{{ msg.description }}</p>
        <div class="d-flex">
          <like-button :message-id="msg.id" :likes="likes"></like-button>
          <favourite-button :message-id="msg.id" :favourites="favourites">
          </favourite-button>
          <hide-button
            :message-id="msg.id"
            :hides="hides"
            :parentStatus="status"
            @statusChanged="status = $event"
          >
          </hide-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["message", "likes", "favourites", "hides"],

  mounted: function () {},

  data: function () {
    let msg = JSON.parse(this.message);
    return {
      msg: msg,
      status: !this.hides.includes(msg.id),
    };
  },

  methods: {},

  computed: {
    show() {
      return this.status;
    },
  },
};
</script>
