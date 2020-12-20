<template>
  <div v-if="showMessage" class="card my-3 w-100" :id="'message-' + msg.id">
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

      <div v-if="userReactions !== null" class="d-flex align-items-center">
        <like-button
          ref="likebtn"
          :message-id="msg.id"
          @statusChanged="setReactions"
          :user-reactions="userReactions"
        ></like-button>
        <favourite-button
          ref="favbtn"
          :message-id="msg.id"
          @statusChanged="setReactions"
          :user-reactions="userReactions"
        >
        </favourite-button>
        <hide-button
          :message-id="msg.id"
          @statusChanged="setReactions"
          :user-reactions="userReactions"
        >
        </hide-button>
      </div>
      <span v-show="reactCount > 0" class="ml-2"
        ><strong
          >{{ reactCount }} reaction<span v-show="reactCount > 1"
            >s</span
          ></strong
        ></span
      >
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

  created: function () {
    this.getUserReactions();
    this.getReactCount();
    this.getTags();
  },
  mounted: function () {
    document.addEventListener("scroll", () => {
      this.updateScroll();
    });
  },

  data: function () {
    return {
      msg: this.message,
      userReactions: null,
      filter: this.query ? this.query : "m",
      tags: null,
      el: null,
      scrollPosition: null,
      pusher: new Pusher("881559dd5578864ceeb0", {
        cluster: "eu",
      }),
      reactCount: 0,
      listening: false,
      loading: true,
    };
  },
  updated: function () {
    if (this.showMessage) {
      this.$nextTick(function () {
        let domElm = document.querySelector("#message-" + this.msg.id);
        this.el = {
          position: this.offset(domElm),
          height: domElm.clientHeight,
        };
      });
    }
  },

  watch: {
    scrollPosition: function () {
      if (this.showMessage) {
        if (
          this.scrollPosition <= this.el.position - 700 ||
          this.scrollPosition > this.el.position + this.el.height
        ) {
          this.unsubscribe();
        } else {
          this.subscribe();
        }
      }
    },
  },
  methods: {
    updateScroll() {
      this.scrollPosition = window.scrollY;
    },
    offset(el) {
      let rect = el.getBoundingClientRect();
      let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      return rect.top + scrollTop;
    },
    setReactions(value) {
      this.userReactions = value;
    },
    subscribe() {
      if (!this.listening) {
        this.getReactCount();
        let channel = this.pusher.subscribe("channel-message-" + this.msg.id);
        channel.bind("notification-push", this.getReactCount, this);
        channel.bind("notification", this.getReactCount, this);
        this.listening = true;
      }
    },
    unsubscribe() {
      if (this.listening) {
        this.pusher.unsubscribe("channel-message-" + this.msg.id);
        this.listening = false;
      }
    },
    async getUserReactions() {
      try {
        let res = await axios.get("/api/m/" + this.msg.id + "/userReactions");
        this.userReactions = res.data;
      } catch (error) {
        console.error(error);
      }
    },
    async getReactCount() {
      try {
        const res = await axios.get("/api/m/" + this.msg.id + "/reactions");
        this.reactCount = res.data.filter((reaction) => {
          return reaction === "fav" || reaction === "like";
        }).length;
      } catch (error) {
        console.error(error);
      }
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
        this.userReactions = ["hide"];
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
    },
  },

  computed: {
    showButtons() {
      return this.$userId ? this.$userId === this.msg.user_id : false;
    },
    showMessage() {
      if (this.userReactions !== null) {
        if (this.filter.includes("likes")) {
          return (
            this.userReactions.includes("like") &&
            !this.userReactions.includes("hide")
          );
        } else {
          if (this.filter.includes("favourites")) {
            return (
              this.userReactions.includes("fav") &&
              !this.userReactions.includes("hide")
            );
          } else {
            if (this.filter.includes("m")) {
              return !this.userReactions.includes("hide");
            } else {
              return this.userReactions.includes("hide");
            }
          }
        }
      }
    },
    show() {
      return this.loading;
    },
  },
  destroyed: function () {
    this.pusher.disconnect();
  },
};
</script>
