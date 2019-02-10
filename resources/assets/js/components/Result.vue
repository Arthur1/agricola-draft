<template>
	<div>
		<ul class="tabs">
			<li v-for="player in players_data" class="tab col s3">
				<a :href="'#tab'+player.player_order" :class="{active: player.name === name}">{{ player.player_order }}番手</a>
			</li>
		</ul>
		<div class="container">
			<div class="collection">
				<div class="collection-item">
					{{ game_data.players_number }}人ゲーム / {{ game_data.regulation }} / {{ game_data.cards_number_description }}<br>
					{{ game_data.owner }}さんが作成 [{{ game_data.created_at }}]
				</div>
			</div>
			<div v-for="player in players_data" :id="'tab'+player.player_order">
				<h3 class="teal-text">{{ player.name }}</h3>
				<div class="row">
					<div class="col s12 l6">
						<h4 class="yellow-text text-darken-2">職業</h4>
						<ul class="collection">
							<li v-for="occupation in occupations_data[Number(player.player_order) - 1]" class="collection-item" v-bind:id="'list'+occupation.card_id">
								{{ occupation.japanese_name }}
								<span :data-target="`modal_`+occupation.card_id" class="modal-trigger new yellow darken-2 badge" data-badge-caption="">{{ occupation.card_id_display }}</span>
							</li>
						</ul>
					</div>
					<div class="col s12 l6">
						<h4 class="orange-text text-darken-2">小さい進歩</h4>
						<ul class="collection">
							<li v-for="improvement in improvements_data[Number(player.player_order) - 1]" class="collection-item" v-bind:id="'list'+improvement.card_id">
								{{ improvement.japanese_name }}
								<span :data-target="`modal_`+improvement.card_id" class="modal-trigger new orange darken-2 badge" data-badge-caption="">{{ improvement.card_id_display }}</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<card-modal v-for="occupation in occupations_list" :key="'modal_'+occupation.card_id" type="occupation" :data="occupation"></card-modal>
		<card-modal v-for="improvement in improvements_list" :key="'modal_'+improvement.card_id" type="improvement" :data="improvement"></card-modal>
	</div>
</template>
<script>
	import http from '../services/http.js'
	import CardModal from '../components/CardModal'
	export default {
		data() {
			return {
				game_data: [],
				players_data: [],
				occupations_data: {},
				improvements_data: {},
				name: '',
			}
		},
		created() {
			http.get('/games/result/' + this.$route.params.game_id, {}, res => {
				this.game_data = res.data.game_data
				this.players_data = res.data.players_data
				this.occupations_data = res.data.occupations_data
				this.improvements_data = res.data.improvements_data
				this.name = res.data.name
				this.$nextTick(() => {
					let el = document.querySelector('.tabs')
					M.Tabs.init(el, {})
				})
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
		},
		mounted() {

		},
		computed: {
			occupations_list() {
				let occupations_list = []
				let occupations_data = this.occupations_data
				for (let i = 0; i < occupations_data.length; i++) {
					Array.prototype.push.apply(occupations_list, occupations_data[i])
				}
				return occupations_list
			},
			improvements_list() {
				let improvements_list = []
				let improvements_data = this.improvements_data
				for (let i = 0; i < improvements_data.length; i++) {
					Array.prototype.push.apply(improvements_list, improvements_data[i])
				}
				return improvements_list
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