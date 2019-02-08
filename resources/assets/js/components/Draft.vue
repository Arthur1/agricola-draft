<template>
	<div>
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
			<h3 class="teal-text">ドラフト{{ picked_order }}手目</h3>
			<p>
				1枚ずつ選択して、「送信」ボタンを押してください。カード番号をクリックすると、詳細情報を確認できます。
			</p>
			<div class="row">
				<div class="col s12 l6">
					<h4 class="yellow-text text-darken-2">職業</h4>
					<ul class="collection">
						<li v-for="occupation in picking_occupations" class="collection-item" v-bind:id="'list'+occupation.card_id">
							<label v-bind:for="'form_'+occupation.card_id"><input type="radio" :value="occupation.card_id" v-bind:id="'form_'+occupation.card_id" v-model="picked_occupation"><span>{{ occupation.japanese_name }}</span></label>
							<span :data-target="`modal_`+occupation.card_id" class="modal-trigger new yellow darken-2 badge" data-badge-caption="">{{ occupation.card_id_display }}</span>
						</li>
					</ul>
				</div>
				<div class="col s12 l6">
					<h4 class="orange-text text-darken-2">小さい進歩</h4>
					<ul class="collection">
						<li v-for="improvement in picking_improvements" class="collection-item" v-bind:id="'list'+improvement.card_id">
							<label v-bind:for="'form_'+improvement.card_id"><input type="radio" :value="improvement.card_id" v-bind:id="'form_'+improvement.card_id" v-model="picked_improvement"><span>{{ improvement.japanese_name }}</span></label>
							<span :data-target="`modal_`+improvement.card_id" class="modal-trigger new orange darken-2 badge" data-badge-caption="">{{ improvement.card_id_display }}</span>
						</li>
					</ul>
				</div>
				<div class="col s12 input-field">
					<button @click="send()" class="btn waves-effect waves-light teal" v-bind:disabled="is_push">送信<i class="material-icons right">send</i></button>
				</div>
			</div>
		</div>
		<card-modal v-for="occupation in picking_occupations" :key="'modal_'+occupation.card_id" type="occupation" :data="occupation"></card-modal>
		<card-modal v-for="improvement in picking_improvements" :key="'modal_'+improvement.card_id" type="improvement" :data="improvement"></card-modal>
	</div>
</template>
<script>
	import http from '../services/http.js'
	import CardModal from '../components/CardModal'
	export default {
		data() {
			return {
				name: '',
				game_data: [],
				players_data: [],
				picking_occupations: [],
				picking_improvements: [],
				picked_order: 0,
				hands_order: 0,
				picked_occupation: '',
				picked_improvement: '',
				is_push: false,
			}
		},
		created() {
			this.name = this.$store.getters.get_name
			http.get('/games/drafts/' + this.$route.params.game_id, {}, res => {
				if (res.data.is_finished) {
					this.$router.push('/result/' + this.$route.params.game_id)
					return
				} else if (! res.data.is_ready) {
					this.$router.push('/draft/waiting/' + this.$route.params.game_id)
					return
				}
				this.game_data = res.data.game_data
				this.players_data = res.data.players_data
				this.picking_occupations = res.data.picking_occupations
				this.picking_improvements = res.data.picking_improvements
				this.picked_order = res.data.picked_order
				this.hands_order = res.data.hands_order
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
						break
					default:
						M.toast({html: 'サーバーエラーです', classes: 'red white-text'})
				}
			})
		},
		methods: {
			send() {
				this.is_push = true
				let params = {
					picked_occupation: this.picked_occupation,
					picked_improvement: this.picked_improvement,
				}
				http.post('/games/drafts/' + this.$route.params.game_id, params, res => {
					this.$router.push('/draft/waiting/' + this.$route.params.game_id)
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
						case 404:
							M.toast({html: err.response.data.error.message, classes: 'red white-text'})
							this.$router.push('/')
							break
						default:
							M.toast({html: 'サーバーエラーです', classes: 'red white-text'})
					}
				})
				this.is_push = false
			}
		},
		components: {
			CardModal
		}
	}
</script>
<style scoped>
	.modal-trigger {
		cursor: pointer;
	}
</style>