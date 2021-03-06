/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

import Vue from 'vue'
import HeaderBar from './components/HeaderBar.vue'
import router from './router'
import store from './stores/index.js'


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('header-bar', HeaderBar)

const app = new Vue({
	router,
	store,
	el: '#app'
})

