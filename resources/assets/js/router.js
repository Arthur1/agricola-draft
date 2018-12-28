import Vue from 'vue';
import VueRouter from 'vue-router';
import Example from './components/Example.vue';

Vue.use(VueRouter);

const routes = [
	{ path: '/', component: Example },
];

const router = new VueRouter({
	routes,
	mode: 'history'
});

export default router;