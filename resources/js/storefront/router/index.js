import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import CatalogView from '../views/CatalogView.vue';
import CategoryView from '../views/CategoryView.vue';
import ProductView from '../views/ProductView.vue';
import CartView from '../views/CartView.vue';

const router = createRouter({
  history: createWebHistory(),
  scrollBehavior: () => ({ top: 0 }),
  routes: [
    { path: '/', name: 'home', component: HomeView },
    { path: '/catalog', name: 'catalog', component: CatalogView },
    { path: '/category/:id', name: 'category', component: CategoryView, props: true },
    { path: '/product/:id', name: 'product', component: ProductView, props: true },
    { path: '/cart', name: 'cart', component: CartView },
  ],
});

export default router;
