/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

import Vue from 'vue'
import VueJWT from 'vuejs-jwt'
import HeaderBar from './components/HeaderBar.vue'
import router from './router'


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('header-bar', HeaderBar)

Vue.use(VueJWT, {
	keyName: 'jwt-token'
})

const app = new Vue({
	router,
	el: '#app'
})

