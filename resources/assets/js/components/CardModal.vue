<template>
	<div class="modal" :id="'modal_'+data.card_id">
		<div class="modal-content">
			<h4 v-if="is_occupation" class="yellow-text text-darken-2">
				{{ data.japanese_name }}
				<span class="new yellow darken-2 badge" data-badge-caption="">{{ data.card_id_display }}</span>
			</h4>
			<h4 v-if="is_improvement" class="orange-text text-darken-2">
				{{ data.japanese_name }}
				<span class="new orange darken-2 badge" data-badge-caption="">{{ data.card_id_display }}</span>
			</h4>
			<dl>
				<dt class="teal-text">デッキ</dt>
				<dd>{{ data.deck_display }}</dd>
				<dt v-if="is_occupation" class="teal-text">カテゴリー</dt>
				<dd v-if="is_occupation" >{{ data.category }}+</dd>
				<dt v-if="is_improvement && data.prerequisite !== ''" class="teal-text">前提</dt>
				<dd v-if="is_improvement && data.prerequisite !== ''">{{ data.prerequisite }}</dd>
				<dt v-if="is_improvement && data.costs !== ''" class="teal-text">コスト</dt>
				<dd v-if="is_improvement && data.costs !== ''">{{ data.costs }}</dd>
				<dt v-if="is_improvement && data.card_points != 0" class="teal-text">カード点</dt>
				<dd v-if="is_improvement && data.card_points != 0">{{ data.card_points }}点</dd>
			</dl>
			<p>
				{{ data.description }}
			</p>
		</div>
	</div>
</template>
<script>
	export default {
		props: [
			'data',
			'type',
		],
		mounted() {
			let elems = document.querySelectorAll('.modal');
			let instances = M.Modal.init(elems, {});
		},
		computed: {
			is_occupation() {
				return this.type === 'occupation'
			},
			is_improvement() {
				return this.type === 'improvement'
			}
		}
	}
</script>