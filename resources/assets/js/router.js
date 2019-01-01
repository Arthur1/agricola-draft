import VueRouter from 'vue-router'
import Vue from 'vue'
import Index from './components/Example.vue'
import Register from './components/Register.vue'

Vue.use(VueRouter)

export default new VueRouter({
	mode: 'history',

	routes: [
		{
			path: '/',
			name: 'Index',
			component: Index,
		},
		{
			path: '/register',
			name: 'ユーザー登録',
			component: Register,
		}
	],

	scrollBehavior (to, from, savedPosition) {
		if (savedPosition) {
			return savedPosition
		} else {
			return { x: 0, y: 0 }
		}
	},
})