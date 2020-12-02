/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

if (document.querySelector("meta[name='user-id']")) {
    Vue.prototype.$userId = document
        .querySelector("meta[name='user-id']")
        .getAttribute("content");
}
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component(
    "follow-button",
    require("./components/ProfileComponents/FollowButton.vue").default
);
Vue.component("like-button", require("./components/LikeButton.vue").default);
Vue.component(
    "favourite-button",
    require("./components/FavouriteButton.vue").default
);
Vue.component("hide-button", require("./components/HideButton.vue").default);
Vue.component(
    "message-component",
    require("./components/MessageComponent.vue").default
);
Vue.component(
    "category-buttons",
    require("./components/ProfileComponents/CategoryButtons.vue").default
);
Vue.component(
    "profile-messages",
    require("./components/ProfileComponents/ProfileMessages.vue").default
);
Vue.component(
    "explore-messages",
    require("./components/ExploreComponents/ExploreMessages.vue").default
);
Vue.component(
    "tag-messages",
    require("./components/TagComponents/TagMessages.vue").default
);
Vue.component(
    "profile-details",
    require("./components/ProfileComponents/ProfileDetails.vue").default
);
Vue.component(
    "profile-container",
    require("./components/ProfileComponents/ProfileContainer.vue").default
);
Vue.component(
    "follows-modal",
    require("./components/ProfileComponents/FollowsModal.vue").default
);
Vue.component(
    "profile-preview",
    require("./components/ProfileComponents/ProfilePreview.vue").default
);
Vue.component("loading-spinner", require("./components/Loading.vue").default);

Vue.component(
    "tag-suggestion",
    require("./components/TagSuggestion.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app"
});
