<template>
	<div>
		<div class="collection">
			<div class="collection-item">テスト</div>
		</div>
		<button class="btn teal" @click="post()">POST</button>
	</div>
</template>

<script>
	import http from '../services/http.js'
	export default {
		mounted: () => {
			console.log('Component mounted.')
		},
		methods: {
			post: () => {
				http.post('/auth/test', {}, res => {
					console.log(res.data)
				}, error => {
					M.toast({html: '「設定」よりicalファイルの登録をしてください', classes: 'red white-text'})
				})
			}
		}
	}
</script>