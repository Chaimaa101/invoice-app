import { createRouter, createWebHistory } from "vue-router";
import invoiceIndex from "../components/invoices/index.vue";
import notFound from "../components/notFound.vue";

const routes = [
    {
        path: '/',
        component: invoiceIndex
    },
    {
        path: '/:pathMatch(.*)*',
        component: notFound
    },

]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router