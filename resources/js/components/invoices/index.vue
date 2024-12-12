<script setup>
    import axios from 'axios';
import { onMounted, ref } from 'vue';

    let invoices = ref([])

    onMounted(async () =>{
        getInvoices()
    });
    const getInvoices = async() =>{
        let response = await axios.get("/api/get_allInvoices")
        console.log('response:', response)
    }
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
            <div class="table--items">
                <a href="#" class="table--items--transactionId">#093654</a>
                <p>Jan 18, 9:31am</p>
                <p>#093654</p>
                <p>Jonathan Yu</p>
                <p>Jan 18, 9:31am</p>
                <p> $ 16,943</p>
            </div>
        </div>
        
    </div>
    </div>
</template>