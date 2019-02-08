<template>
	<div>
		<div class="container">
			<h1 class="orange-text">ユーザー登録</h1>
			<p>
				登録の前に、<a href="#modal-terms" class="modal-trigger">利用規約</a>をご覧ください。
			</p>
			<h2 class="green-text">入力フォーム</h2>
			<div class="row">
				<div class="col input-field s12">
					<input placeholder="英数字" type="text" name="name" id="form_name" class="validate" required v-model="name">
					<label for="form_name">ユーザー名</label>
				</div>
				<div class="col input-field s12">
					<input placeholder="英数字" type="email" name="email" id="form_email" class="validate" required v-model="email">
					<label for="form_email">メールアドレス</label>
				</div>
				<div class="col input-field s12">
					<input placeholder="" type="password" name="password" id="form_password" class="validate" required v-model="password">
					<label for="form_email">パスワード</label>
				</div>
				<div class="col input-field s12">
					<input placeholder="" type="password" name="password_check" id="form_password_check" class="validate" required v-model="password_check">
					<label for="form_email_check">パスワード(確認)</label>
				</div>
				<div class="col input-field s12">
					<button @click="register()" class="btn waves-effect waves-light teal" v-bind:disabled="is_push">登録<i class="material-icons right">send</i></button>
				</div>
			</div>
		</div>
		<div class="modal" id="modal-terms">
			<div class="modal-content">
				<h3>利用規約</h3>
				<p>
					本アプリケーションの使用に関連してお客様自身または第三者に生じた損害について、その賠償の責任を一切負いかねますことご了承ください。
				</p>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-close waves-effect waves-black btn-flat">閉じる</a>
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
				password_check: '',
				email: '',
				is_push: false,
			}
		},
		mounted: () => {
			let elems = document.querySelectorAll('.modal');
			let instances = M.Modal.init(elems, {});
			M.updateTextFields()
		},
		methods: {
			register() {
				this.is_push = true
				let params = {
					name: this.name,
					email: this.email,
					password: this.password,
					password_check: this.password_check
				}
				this.$store.dispatch('register', params).then(res => {
					M.toast({html: 'ユーザー登録に成功しました', classes: 'teal white-text'})
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