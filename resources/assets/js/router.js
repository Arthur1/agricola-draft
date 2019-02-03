import VueRouter from 'vue-router'
import Vue from 'vue'
import Index from './components/Home.vue'
import Register from './components/Register.vue'
import Login from './components/Login.vue'

Vue.use(VueRouter)

const router =new VueRouter({
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
		},
		{
			path: '/login',
			name: 'ログイン',
			component: Login,
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

router.beforeEach((to, from, next) => {
	const publicPages = ['/login', '/register', '/feature']
	const authRequired = !publicPages.includes(to.path)
	const loggedIn = localStorage.getItem('jwt-token')
	if (authRequired && ! loggedIn) {
		return next('/login')
	}
	next()
})

export default router