<template>
  <div>
    <ul class="list-group">
      <li
        v-for="suggestion in suggestions"
        :key="suggestion.id"
        @click="selectTag(suggestion)"
        class="list-group-item d-flex justify-content-between align-items-center"
        style="cursor: pointer"
      >
        <img
          src="/svg/hashtag.svg"
          class="rounded-circle border border-dark p-1 mr-3"
          alt=""
          srcset=""
        />
        <strong>{{ suggestion.label }}</strong>
        <span class="badge badge-primary badge-pill ml-3">{{
          suggestion.messages_count
        }}</span>
      </li>
    </ul>
  </div>
</template>

<script>
var _ = require("lodash/function");
export default {
  props: [""],
  mounted() {
    let input = document.querySelector("#hashtags");
    input.addEventListener("input", () => {
      this.text = input.value;
      this.updateField();
    });
  },

  data: function () {
    return {
      text: null,
      suggestions: null,
    };
  },
  methods: {
    selectTag(suggestion) {
      let input = document.querySelector("#hashtags");
      input.value = suggestion.label + ",";
      this.suggestions = null;
    },
    updateField: _.debounce(function () {
      this.getSuggestions();
    }, 300),
    async getSuggestions() {
      try {
        if (this.text) {
          let res = await axios.get("/api/tag/" + this.text + "/suggestions");

          this.suggestions = res.data;
        } else {
          this.suggestions = null;
        }
      } catch (error) {
        console.error(error);
      }
    },
  },

  computed: {},
};
</script>
