<template>
	<div class="container">
		<p>
			登録した情報を入力してログインしてください。未登録の方は、<router-link to="/register">登録フォーム</router-link>よりご登録ください。
		</p>
		<div class="row">
			<div class="col input-field s12">
				<input placeholder="英数字" type="text" name="name" id="form_name" class="validate" required v-model="name">
				<label for="form_name">ユーザー名</label>
			</div>
			<div class="col input-field s12">
				<input placeholder="" type="password" name="password" id="form_password" class="validate" required v-model="password">
				<label for="form_email">パスワード</label>
			</div>
			<div class="col s12 input-field">
				<button class="btn teal" @click="login()" v-bind:disabled="is_push">ログイン</button>
			</div>
		</div>
	</div>
</template>

<script>
	import http from '../services/http.js'
	export default {
		data() {
			return {
				name: '',
				password: '',
				is_push: false,
			}
		},
		mounted: () => {
			M.updateTextFields()
		},
		methods: {
			login() {
				this.is_push = true
				let params = {
					name: this.name,
					password: this.password
				}
				this.$store.dispatch('login', params).then(res => {
					M.toast({html: 'ログインに成功しました', classes: 'teal white-text'})
					this.$router.push('/')
				}).catch(err => {
					switch (err.response.status) {
						case 400:
							for (let message of err.response.data.error.messages) {
								M.toast({html: message, classes: 'red white-text'})
							}
							break;
						case 401:
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