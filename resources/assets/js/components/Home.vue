<template>
	<div class="container">
		<p>{{ name }}さん、ようこそ！</p>
		<h2 class="orange-text">進行中のゲーム</h2>
		<div class="collection">
			<router-link v-for="game of games_in_progress" :key="game.game_id" :to="'/draft/' + game.game_id" class="collection-item">
				{{ game.players_number }}人ゲーム / {{ game.player_order }}番手 / {{ game.regulation }} / {{ game.cards_number_description }}<br>
				{{ game.owner }}さんが作成 [{{ game.created_at }}]
			</router-link>
		</div>
	</div>
</template>
<script>
	import http from '../services/http.js'
	export default {
		data() {
			return {
				name: '',
				games_in_progress: [],
			}
		},
		mounted() {
			let jwt = this.$jwt.decode()
			console.log(jwt)
			this.name = jwt.name
			http.get('/games/in_progress', {}, res => {
				console.log(res.data)
				this.games_in_progress = res.data
			}, err => {
				switch (err.response.status) {
					case 400:
						for (let message of err.response.data.error.messages) {
							M.toast({html: message, classes: 'red white-text'})
						}
						break
					case 401:
						M.toast({html: err.response.data.error.message, classes: 'red white-text'})
						this.$router.push('/login')
						break
					case 403:
						M.toast({html: err.response.data.error.message, classes: 'red white-text'})
						break
					default:
						M.toast({html: 'サーバーエラーです', classes: 'red white-text'})
				}
			})
		}
	}
</script>