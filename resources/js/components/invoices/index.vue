<script setup>
    import axios from 'axios';
import { onMounted, ref } from 'vue';

    const invoices = ref([])

   
    const getInvoices = async () => {
await axios.get('http://localhost:8000/api/invoices')
    .then(res => invoices.value = res.data.invoices)
    .catch(error => {
        console.error(error);
    });
};
 onMounted(async () =>{
        getInvoices()
    });

</script>
<template>
    
    <div class="container">
        
    <div class="invoices">
        
        <div class="card__header">
            <div>
                <h2 class="invoice__title">Invoices</h2>
            </div>
            <div>
                <a class="btn btn-secondary">
                    New Invoice
                </a>
            </div>
        </div>

        <div class="table card__content">
            <div class="table--filter">
                <span class="table--filter--collapseBtn ">
                    <i class="fas fa-ellipsis-h"></i>
                </span>
                <div class="table--filter--listWrapper">
                    <ul class="table--filter--list">
                        <li>
                            <p class="table--filter--link table--filter--link--active">
                                All
                            </p>
                        </li>
                        <li>
                            <p class="table--filter--link ">
                                Paid
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table--search">
                <div class="table--search--wrapper">
                    <select class="table--search--select" name="" id="">
                        <option value="">Filter</option>
                    </select>
                </div>
                <div class="relative">
                    <i class="table--search--input--icon fas fa-search "></i>
                    <input class="table--search--input" type="text" placeholder="Search invoice">
                </div>
            </div>

            <div class="table--heading">
                <p>ID</p>
                <p>Date</p>
                <p>Number</p>
                <p>Customer</p>
                <p>Due Date</p>
                <p>Total</p>
            </div>

            <!-- item 1 -->
            <div class="table--items " v-for="item in invoices" :key="item.id" v-if="invoices.length > 0">
                <a href="#" class="table--items--transactionId">#093654</a>
                <p>{{item.date}}</p>
                <p>#{{ item.number }}</p>
                <p>{{ item.customer_id }}</p>
                <p>{{item.due_date}}</p>
                <p> ${{ item.total}}</p>
            </div>
            <div class="table--items" v-else>
                <p>No invoices founded.</p>
            </div>
        </div>
        
    </div>
    </div>
</template>