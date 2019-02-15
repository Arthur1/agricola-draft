<template>
	<div class="container">
		<p>
			ゲームの詳細を入力してください。
		</p>
		<div class="row">
			<div class="col s12 input-field">
				<select v-model.number="players_number">
					<option value="0" disabled selected>[未選択]</option>
					<option value="2">2人</option>
					<option value="3">3人</option>
					<option value="4">4人</option>
					<option value="5">5人</option>
					<option value="6">6人</option>
				</select>
				<label>プレイ人数</label>
			</div>
			<div class="col s12 input-field">
				<select v-model.number="regulation_type">
					<option value="0" disabled selected>[未選択]</option>
					<option value="1">旧版基本(EIK)</option>
					<option value="2">旧版拡張</option>
					<option value="3">リバイズド基本＋5-6人拡張</option>
					<option value="4">リバイズド拡張</option>
				</select>
				<label>レギュレーション</label>
			</div>
			<div class="col s12 input-field">
				<select v-model.number="cards_number">
					<option value="" disabled selected>[未選択]</option>
					<option value="7">7枚ドラフト</option>
					<option value="8">8-7枚ドラフト</option>
					<option value="10">10-7枚ドラフト</option>
				</select>
				<label>カード枚数</label>
			</div>
			<div v-for="i in players_number" class="col s12 input-field">
				<input type="text" v-model="players[i - 1]" :id="'form_player' + i" class="active">
				<label :fot="'form_player' + i">ユーザー名({{ i }}人目)</label>
			</div>
			<div class="col input-field s12">
				<button @click="create()" class="btn waves-effect waves-light teal" v-bind:disabled="is_push">作成<i class="material-icons right">send</i></button>
			</div>
		</div>
	</div>
</template>
<script>
	import http from '../services/http.js'
	export default {
		data() {
			return {
				players_number: 0,
				regulation_type: 0,
				cards_number: 0,
				players: [],
				is_push: false,
			}
		},
		watch: {
			players_number: function() {
				this.$nextTick(() => {M.updateTextFields()})
			}
		},
		mounted() {
			let elems = document.querySelectorAll('select')
			let instances = M.FormSelect.init(elems, {})
			this.players[0] = this.$store.getters.get_name
			M.updateTextFields()
		},
		methods: {
			create() {
				this.is_push = true
				let players = this.players
				players.length = this.players_number
				let params = {
					players_number: this.players_number,
					regulation_type: this.regulation_type,
					cards_number: this.cards_number,
					players: players,
				}
				http.post('/games/create', params, res => {
					M.toast({html: 'ゲームを作成しました', classes: 'teal white-text'})
					this.$router.push('/')
				}, err => {
					switch (err.response.status) {
						case 400:
							for (let message of err.response.data.error.messages) {
								M.toast({html: message, classes: 'red white-text'})
							}
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
				this.is_push = false
			}
		}
	}
</script>