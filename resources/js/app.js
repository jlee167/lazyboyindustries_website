/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//const { data } = require('jquery');

//require('./bootstrap');


//import Vue from 'vue';
//import InputTag from 'vue-input-tag';
//import StarRating from 'vue-star-rating';
import Notifications from 'vue-notification';



window.Vue = require('vue');
Vue.use(Notifications);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./vue-components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('input-tag', () => import('vue-input-tag'));
Vue.component('star-rating', () => import('vue-star-rating'));

Vue.component('pulse-loader', () => import('vue-spinner/src/PulseLoader'));

Vue.component('scroll-arrow', require('./vue-components/ScrollArrow').default);

Vue.component('user-list-display', () => import('./vue-components/UserListDisplay'));
Vue.component('skill-bar', () => import('./vue-components/SkillBar'));
Vue.component('peer-list', () => import('./vue-components/PeerList'));
Vue.component('product-desc-view', () => import('./vue-components/ProductDescView'));
Vue.component('product-sales', () => import('./vue-components/ProductSales'));
Vue.component('video-js', () => import('./vue-components/VideoJs'));
Vue.component('forum-post-list', () => import('./vue-components/ForumPostList'));
Vue.component('trending-posts', require('./vue-components/TrendingPosts.vue').default);
Vue.component('forum-post', () => import('./vue-components/ForumPost'));
Vue.component('summer-note', () => import('./vue-components/Summernote'));
Vue.component('product-card', () => import('./vue-components/ProductCard'));
Vue.component('skill-list', require('./vue-components/SkillList.vue').default);
Vue.component('skill-level', require('./vue-components/SkillLevel.vue').default);
Vue.component('post-tag', () => import('./vue-components/PostTag'));
Vue.component('qna-dropdown', () => import('./vue-components/QnaDropdown'));
Vue.component('button-array', () => import('./vue-components/ButtonArray'));
//Vue.component('chat-room', require('./vue-components/ChatRoom.vue').default);





