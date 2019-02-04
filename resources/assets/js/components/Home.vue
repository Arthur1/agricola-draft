<template>
	<div class="container">
		<p>{{ name }}さん、ようこそ！</p>
		<h2 class="orange-text">進行中のドラフト</h2>
		<h2 class="orange-text">招待されたドラフト</h2>
	</div>
</template>
<script>
	import VueJWT from 'vuejs-jwt'
	export default {
		data() {
			return {
				name: ''
			}
		},
		mounted() {
			let jwt = this.$jwt.decode()
			console.log(jwt)
			this.name = jwt.name
		}
	}
</script>