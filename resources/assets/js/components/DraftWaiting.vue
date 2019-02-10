<template>
	<div>
		<div class="progress">
			<div class="indeterminate"></div>
		</div>
		<div class="container">
			<h3 class="teal-text">ゲーム詳細</h3>
			<div class="collection">
				<div class="collection-item">
					{{ game_data.players_number }}人ゲーム / {{ game_data.regulation }} / {{ game_data.cards_number_description }}<br>
					{{ game_data.owner }}さんが作成 [{{ game_data.created_at }}]
				</div>
				<div class="collection-item">
					<div v-for="player of players_data" :key="player.player_order" v-bind:class="{ 'teal-text': name === player.name }">
						{{ player.player_order }}番手: {{ player.name }}
					</div>
				</div>
			</div>
			<h3 class="teal-text">待機中</h3>
			他のプレイヤーの入力完了まで待機しています。
			<h3 class="teal-text">現在の手札</h3>
			<p>
				カード番号をクリックすると、詳細情報を確認できます。
			</p>
			<div class="row">
				<div class="col s12 l6">
					<h4 class="yellow-text text-darken-2">職業</h4>
					<ul class="collection">
						<li v-for="occupation in picked_occupations" class="collection-item" v-bind:id="'list'+occupation.card_id">
							{{ occupation.japanese_name }}
							<span :data-target="`modal_`+occupation.card_id" class="modal-trigger new yellow darken-2 badge" data-badge-caption="">{{ occupation.card_id_display }}</span>
						</li>
					</ul>
				</div>
				<div class="col s12 l6">
					<h4 class="orange-text text-darken-2">小さい進歩</h4>
					<ul class="collection">
						<li v-for="improvement in picked_improvements" class="collection-item" v-bind:id="'list'+improvement.card_id">
							{{ improvement.japanese_name }}
							<span :data-target="`modal_`+improvement.card_id" class="modal-trigger new orange darken-2 badge" data-badge-caption="">{{ improvement.card_id_display }}</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<card-modal v-for="occupation in picked_occupations" :key="'modal_'+occupation.card_id" type="occupation" :data="occupation"></card-modal>
		<card-modal v-for="improvement in picked_improvements" :key="'modal_'+improvement.card_id" type="improvement" :data="improvement"></card-modal>
	</div>
</template>
<script>
	import http from '../services/http.js'
	import CardModal from '../components/CardModal'
	export default {
		data() {
			return {
				name: '',
				game_data: {},
				players_data: {},
				picked_occupations: [],
				picked_improvements: [],
				interval: null,
			}
		},
		created() {
			this.name = this.$store.getters.get_name
			let game_id = this.$route.params.game_id
			http.get('/games/waiting/' + game_id, {}, res => {
				this.game_data = res.data.game_data
				this.players_data = res.data.players_data
				this.picked_occupations = res.data.picked_occupations
				this.picked_improvements = res.data.picked_improvements
			}, err => {
				switch (err.response.status) {
					case 404:
						M.toast({html: err.response.data.error.message, classes: 'red white-text'})
						this.$router.push('/')
						break
					case 401:
						M.toast({html: err.response.data.error.message, classes: 'red white-text'})
						this.$store.dispatch('logout')
						this.$router.push('/login')
						break
					case 403:
						M.toast({html: err.response.data.error.message, classes: 'red white-text'})
						this.$router.push('/')
						break
					default:
						M.toast({html: 'サーバーエラーです', classes: 'red white-text'})
						//this.$router.push('/')
				}
			})
			this.interval = setInterval(() => {
      			http.get('/games/is_ready/' + game_id, {}, res => {
					if (res.data.is_finished) {
						this.$router.push('/result/' + game_id)
					} else if (res.data.is_ready) {
						this.$router.push('/draft/' + game_id)
					}
				}, err => {
					switch (err.response.status) {
						case 404:
							M.toast({html: err.response.data.error.message, classes: 'red white-text'})
							this.$router.push('/')
							break
						case 401:
							M.toast({html: err.response.data.error.message, classes: 'red white-text'})
							this.$store.dispatch('logout')
							this.$router.push('/login')
							break
						case 403:
							M.toast({html: err.response.data.error.message, classes: 'red white-text'})
							this.$router.push('/')
							break
						default:
							M.toast({html: 'サーバーエラーです', classes: 'red white-text'})
							//this.$router.push('/')
					}
				})
    		}, 3 * 1000)
		},
		beforeDestroy() {
			clearInterval(this.interval)
		},
		components: {
			CardModal
		}
	}
</script>
<style scoped>
	.progress {
		margin: 0;
	}
	.modal-trigger {
		cursor: pointer;
	}
</style>
