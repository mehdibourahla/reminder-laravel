<template>
  <div v-if="showMessage" class="card my-3 w-100">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <a
            :href="'/profile/' + msg.user_id"
            class="d-flex align-items-center"
          >
            <loading-spinner
              v-show="show"
              v-bind:style="{ width: '50px' }"
            ></loading-spinner>

            <img
              :src="getImg()"
              v-show="!show"
              @load="loaded"
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
          :is-liked="isLiked"
          @statusChanged="like"
        ></like-button>
        <favourite-button
          :message-id="msg.id"
          :is-favourite="isFavourite"
          @statusChanged="fav"
        >
        </favourite-button>
        <hide-button
          :message-id="msg.id"
          :is-hidden="isHidden"
          @statusChanged="hide"
        >
        </hide-button>
      </div>
      <div class="mt-2">
        <a
          v-for="tag in tags"
          :key="tag.id"
          :href="'/tag/' + tag.id"
          class="btn btn-outline-info btn-sm"
          style="margin: 2px 3px"
        >
          {{ tag.label }}
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: ["message", "query"],

  mounted: function () {},

  data: function () {
    return {
      msg: this.message,
      filter: this.query ? this.query : "m",
      isLiked: this.message.type ? this.message.type.includes("like") : false,
      isFavourite: this.message.type
        ? this.message.type.includes("fav")
        : false,
      isHidden: this.message.type ? this.message.type.includes("hide") : false,
      tags: null,
      loading: true,
    };
  },

  methods: {
    like(value) {
      this.isLiked = value;
    },
    fav(value) {
      this.isFavourite = value;
    },
    hide(value) {
      this.isHidden = value;
    },

    async getTags() {
      try {
        let res = await axios.get("/api/m/" + this.msg.id + "/tags");
        this.tags = res.data;
      } catch (error) {
        console.error(error);
      }
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
    getImg() {
      const res = this.msg.picture
        ? "/storage/" + this.msg.picture
        : "/svg/user.svg";
      return res;
    },
    loaded() {
      this.loading = false;
      this.getTags();
    },
  },

  computed: {
    showButtons() {
      return this.$userId ? this.$userId === this.msg.user_id : false;
    },
    showMessage() {
      console.log(this.filter);
      if (this.filter.includes("likes")) {
        return this.isLiked && !this.isHidden;
      } else {
        if (this.filter.includes("favourites")) {
          return this.isFavourite && !this.isHidden;
        } else {
          if (this.filter.includes("m")) {
            return !this.isHidden;
          } else {
            console.log(this.isHidden);
            return this.isHidden;
          }
        }
      }
    },
    show() {
      return this.loading;
    },
  },
};
</script>
