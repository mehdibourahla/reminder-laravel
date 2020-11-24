<template>
  <div v-if="showMessage" class="card my-3 w-100">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between">
        <div class="">
          <a :href="'/profile/' + msg.user_id">
            <img
              :src="getImgUrl(msg)"
              alt="profile-pic"
              class="img-thumbnail rounded-circle"
              style="width: 50px"
            />
            <span class="text-dark font-weight-bold">{{ msg.username }}</span>
          </a>
        </div>
        <div v-if="showButtons">
          <a :href="'/m/' + msg.id + '/edit'" class="btn btn-success">Edit</a>
          <button @click="deleteMessage(msg)" class="btn btn-danger">
            Delete
          </button>
        </div>
      </div>
      <div class="pl-2 py-3">
        <a :href="'/m/' + msg.id" class="text-dark">
          <h5 class="card-title font-weight-bold">{{ msg.caption }}</h5>
        </a>
        <p class="card-text">
          {{ msg.description }}
        </p>
        <h6 class="card-subtitle mb-2 text-muted">{{ msg.created_at }}</h6>
      </div>

      <div class="d-flex align-items-center">
        <like-button
          :message-id="msg.id"
          :likesCount="likesCount"
          :is-liked="isLiked"
          @statusChanged="like"
        ></like-button>
        <favourite-button
          :message-id="msg.id"
          :favCount="favCount"
          :is-favourite="isFavourite"
          @statusChanged="fav"
        >
        </favourite-button>
        <hide-button
          :message-id="msg.id"
          :is-hidden="isHidden"
          @statusChanged="isHidden = $event"
        >
        </hide-button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["message", "query", "user"],

  mounted: function () {
    this.getLikeCount();
    this.getFavCount();
  },

  data: function () {
    return {
      msg: this.message,
      isLiked: this.message.type.includes("like"),
      isFavourite: this.message.type.includes("fav"),
      isHidden: this.message.type.includes("hide"),
      likesCount: 0,
      favCount: 0,
    };
  },

  methods: {
    like(value) {
      this.isLiked = value;
      this.getLikeCount();
    },
    fav(value) {
      this.isFavourite = value;
      this.getFavCount();
    },
    async getLikeCount() {
      try {
        const res = await axios.get("/m/" + this.msg.id + "/likes");
        this.likesCount = res.data.length;
      } catch (error) {
        console.error(error);
      }
    },
    async getFavCount() {
      try {
        const res = await axios.get("/m/" + this.msg.id + "/fav");
        this.favCount = res.data.length;
      } catch (error) {
        console.error(error);
      }
    },
    getImgUrl(msg) {
      return "/storage/" + msg.picture;
    },
    async deleteMessage(msg) {
      let res = await axios.delete("/m/" + msg.id);
      if (res.status !== 200) {
        console.error(res);
      } else {
        this.isHidden = true;
        this.$emit("deleted", true);
      }
    },
  },

  computed: {
    showButtons() {
      return this.user ? JSON.parse(this.user).id == this.msg.user_id : false;
    },
    showMessage() {
      if (this.query.includes("likes")) {
        return this.isLiked && !this.isHidden;
      } else {
        if (this.query.includes("fav")) {
          return this.isFavourite && !this.isHidden;
        } else {
          if (this.query.includes("m")) {
            return !this.isHidden;
          } else {
            return this.isHidden;
          }
        }
      }
    },
  },
};
</script>
