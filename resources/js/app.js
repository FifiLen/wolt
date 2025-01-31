import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';


import HomePage from './pages/HomePage.vue';
import LoginPage from './pages/LoginPage.vue';
import RegisterPage from './pages/RegisterPage.vue';
import RestaurantsPage from "./pages/RestaurantsPage.vue";
import RestaurantMenu from "./pages/RestaurantMenu.vue";
import Orders from "./pages/Orders.vue";
import OrderPage from "./pages/OrderPage.vue";

const routes = [
    { path: '/', component: HomePage },
    { path: '/login', component: LoginPage },
    { path: '/register', component: RegisterPage },
    { path: '/restaurants', component: RestaurantsPage },
    { path: '/restaurants/:id/menu', component: RestaurantMenu },
    { path: '/orders', component: Orders },
    { path: '/orders/:id', component: OrderPage }

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


const app = createApp(App);
app.use(router);
app.mount('#app');
