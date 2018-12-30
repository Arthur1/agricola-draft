<template>
	<div>
		<ul class="sidenav" id="slide-out">
			<li><router-link class="sidenav-close" to="/login">ログイン</router-link></li>
			<li><router-link class="sidenav-close" to="/register">ユーザー登録</router-link></li>
		</ul>
		<header class="navbar-fixed">
			<nav>
				<div class="nav-wrapper orange">
					<div class="hide-on-med-and-down left">
						<router-link to="/" class="breadcrumb">Agricola Online Draft</router-link>
						<router-link v-if="this.$route.path !== '/'" :to="this.$route.path" class="breadcrumb">{{ this.$route.name }}</router-link>
					</div>
					<router-link :to="this.$route.path" class="hide-on-large-only" style="font-size: 1.3em;">{{ this.$route.name }}</router-link>
					<a v-if="this.$route.path === '/'" href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
					<router-link v-else to="/" id="back-trigger" class="hide-on-large-only"><i class="material-icons">arrow_back</i></router-link>
					<ul class="right hide-on-med-and-down">
						<li><router-link class="sidenav-close" to="/login">ログイン</router-link></li>
						<li><router-link class="sidenav-close" to="/register">ユーザー登録</router-link></li>
					</ul>
				</div>
			</nav>
		</header>
	</div>
</template>
<script>
	export default {
		mounted() {
			let el = document.querySelector('.sidenav');
			let instance = M.Sidenav.init(el, {});
		},
	}
</script>
<style scoped>
	[id="back-trigger"] {
		float: left;
		position: relative;
		z-index: 1;
		height: 56px;
		margin: 0 18px;
	}
	[id="back-trigger"] > i {
		height: 56px;
		line-height: 56px;
	}
	@media only screen and (min-width: 601px) {
		[id="back-trigger"], [id="back-trigger"] > i {
			height: 64px;
			line-height: 64px;
		}
	}
</style>