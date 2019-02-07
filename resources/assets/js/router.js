import VueRouter from 'vue-router'
import Vue from 'vue'
import Home from './components/Home.vue'
import Register from './components/Register.vue'
import Login from './components/Login.vue'
import CreateGame from './components/CreateGame.vue'
import Draft from './components/Draft.vue'
import DraftWaiting from './components/DraftWaiting.vue'

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',

	routes: [
		{
			path: '/',
			name: 'Home',
			component: Home,
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
		{
			path: '/create_game',
			name: 'ゲーム作成',
			component: CreateGame,
		},
		{
			path: '/draft/:game_id',
			name: 'ドラフト',
			component: Draft,
		},
		{
			path: '/draft/waiting/:game_id',
			name: '待機中',
			component: DraftWaiting,
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