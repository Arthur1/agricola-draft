import http from '../services/http'

export default {
	state: {
		name: null,
		email: null,
		is_logged_in: false,
		gravatar: null,
	},

	mutations: {
		login(state, decoded_token) {
			state.is_logged_in = true
			state.name = decoded_token.name
			state.email = decoded_token.email
			state.gravatar = decoded_token.gravatar
		},

		logout(state) {
			state.is_logged_in = false
			state.name = null
			state.email = null
			state.gravatar = null
		},
	},

	actions: {
		login({commit}, params) {
			return new Promise((resolve, reject) => {
				http.post('/auth/login', params, res => {
					commit('login', res.data)
					localStorage.setItem('jwt-token', res.data.token)
					resolve(res)
				}, error => {
					commit('logout')
					localStorage.removeItem('jwt-token')
					reject(error)
				})
			})
		},

		register({commit}, params) {
			return new Promise((resolve, reject) => {
				http.post('/auth/register', params, res => {
					commit('login', res.data)
					localStorage.setItem('jwt-token', res.data.token)
					resolve(res)
				}, error => {
					commit('logout')
					localStorage.removeItem('jwt-token')
					reject(error)
				})
			})
		},

		logout({commit}) {
			localStorage.removeItem('jwt-token')
			commit('logout')
		},

		init({commit}) {
			return new Promise((resolve, reject) => {
				http.get('/auth/me', {}, res => {
					commit('login', res.data)
					resolve()
				}, error => {
					commit('logout')
					reject()
				})
			})
		},
	},

	getters: {
		is_logged_in: state => {
			return state.is_logged_in
		},

		get_name: state => {
			return state.name
		},

		get_email: state => {
			return state.email
		},

		get_gravatar: state => {
			return state.gravatar
		},
	},
}