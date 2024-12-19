import { createRouter, createWebHistory } from "vue-router";
import invoiceIndex from "../components/invoices/index.vue";
import notFound from "../components/notFound.vue";
import invoiceNew from '../components/invoices/new.vue'
import invoiceShow from '../components/invoices/show.vue'

const routes = [
    {
        path: '/',
        component: invoiceIndex
    },
    {
        path: '/invoice/new',
        component: invoiceNew
    },
    {
        path: '/:pathMatch(.*)*',
        component: notFound
    },
    {
        path: '/invoices/show/:id',
        component: invoiceShow,
        props: true
    },

]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router