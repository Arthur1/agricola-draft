import VueRouter from 'vue-router'
import Vue from 'vue'
import Index from './components/Example.vue'

Vue.use(VueRouter)

export default new VueRouter({
	mode: 'history',

	routes: [
		{
			path: '/',
			name: 'Index',
			component: Index,
		},
	],

	scrollBehavior (to, from, savedPosition) {
		if (savedPosition) {
			return savedPosition
		} else {
			return { x: 0, y: 0 }
		}
	},
})