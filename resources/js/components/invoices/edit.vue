<script setup>

import { onMounted, ref } from 'vue';
import axios from 'axios';

let invoice = ref({ id: '' })
let listProducts = ref([])
let customer_id = ref([])
let customers = ref([])
let listCart = ref([])
const showModal = ref(false)
const hideModal = ref(true)

onMounted(async () => {
    getCustomers()
    getInvoice()
    getProducts()
})

const props = defineProps({
    id: {
        type: String,
        default: ''
    }
})

const addCart = (item) => {
    const itemCart = {
        id: item.id,
        item_code: item.item_code,
        description: item.description,
        unit_price:parseFloat( item.unit_price),
        quantity: item.quantity
    }
    listCart.value.push(itemCart)
    closeModal()
}

const getInvoice = async () => {
    try {
        const res = await axios.get(`/invoices/${props.id}`);
        invoice.value = res.data.data;
        console.log(invoice.value);
    } catch (error) {
        console.error('Error fetching invoice:', error);
    }
};

const getCustomers = async () => {
    try {
        const response = await axios.get('/customers');
        customers.value = response.data.data
    } catch (error) {
        console.error('Error fetching customers:', error);
    }
}

const getProducts = async () => {
    try {
        const response = await axios.get('/products');
        listProducts.value = response.data.data
    } catch (error) {
        console.error('Error fetching customers:', error);
    }
}

const openModal = () => {
    showModal.value = !showModal.value
}
const closeModal = () => {
    showModal.value = !hideModal.value
}
const subTotal = () => {
    let total = 0
    listCart.value.map((data)=>{
        total = total+(data.quantity*data.unit_price)
    })
    return total
}
 const Total = () =>{
        return subTotal() - (invoice.value.discount)

}

const onEdit = async () => {
    if (listCart.value.length >= 1) {
        const subtotal = subTotal();
        const total = Total();

        const formData = {
            invoiceItem: listCart.value,
            customer_id: invoice.value.customer_id, // Fetch from `invoice`
            date: invoice.value.date,
            due_date: invoice.value.due_date,
            number: invoice.value.number,
            reference: invoice.value.reference,
            discount: invoice.value.discount,
            sub_total: subtotal,
            total: total,
            terms_and_conditions: invoice.value.terms_and_conditions,
        };

        try {
            console.log('DATA', formData);
            await axios.post(`/invoices/${invoice.value.id}`, formData);
            listCart.value = [];
            router.push('/');
        } catch (error) {
            console.error('Error saving invoice:', error);
        }
    } else {
        console.log("Cart is empty, cannot save invoice.");
    }
};


const deleteinvoiceItem = (id,i) =>{
 invoice.value.invoiceItems.splite(i, 1)
 if (id != undefined){
    try {
        axios.delete('/invoiceItems')
    } catch (error) {
        console.error('Error deleting invoice Item:', error);
        
    }
 }
}

</script>

<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Edit Invoice</h2>
                </div>
                <div>

                </div>
            </div>

            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select v-model="invoice.customer_id" class="input">
                            <option disabled value="">Select a customer</option>
                            <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                {{ customer.firstname }} {{ customer.lastname }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p>
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="invoice.date">
                        <!---->
                        <p class="my-1">Due date</p>
                        <input id="due_date" type="date" class="input" v-model="invoice.due_date">
                    </div>
                    <div>
                        <p class="my-1">Number</p>
                        <input type="text" class="input" v-model="invoice.number">
                        <p class="my-1">Reference</p>
                        <input type="text" class="input" v-model="invoice.reference">
                    </div>
                </div>
                <br><br>
                <div class="table">

                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>

                    <!-- item 1 -->
                    <div class="table--items2" v-for="(item, i) in invoice.invoiceItems">
                        <p v-if="item.product"># {{ item.product.item_code }} {{item.product.description}}</p>
                        <p>
                            <input type="text" class="input" v-model="item.unit_price">
                        </p>
                        <p>
                            <input type="text" class="input" v-model="item.quantity">
                        </p>
                        <p>
                            {{ item.unit_price * item.quantity }}
                        </p>
                        <p style="color: red; font-size: 24px;cursor: pointer;" @click="deleteinvoiceItem(item.id , i)">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal" @click="openModal()">Add New Line</button>
                    </div>
                </div>

                <div class="table__footer">
                    <div class="document-footer">
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea" v-model="invoice.terms_and_conditions"></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>{{subTotal()}} Dh</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input" v-model="invoice.discount">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>{{Total()}} Dh</span>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>

                </div>
                <div>
                    <a class="btn btn-secondary" @click="onEdit()">
                        Save
                    </a>
                </div>
            </div>

        </div>
    </div>
    <!--==================== add modal items ====================-->
    <div class="modal main__modal ">
        <div class="modal__content">
            <span class="modal__close btn__close--modal">×</span>
            <h3 class="modal__title">Add Item</h3>
            <hr><br>
            <div class="modal__items">
                 <ul>
                        <li v-for="(item, i) in listProducts" :key="item.id"
                            style="display:grid;grid-template-columns: 30px 350px 15px;align-items: center;padding-bottom: 5px;">
                            <p>{{ i + 1 }}</p>
                            <a href="#">{{ item.item_code }} {{ item.description }} {{item.unit_price}}</a>
                            <button @click="addCart(item)"
                                style="border: 1px solid #e0e0e0;width: 35px; height: 35px; cursor: pointer;"> +
                            </button>
                        </li>
                    </ul>
            </div>
            <br>
            <hr>
            <div class="model__footer">
                <button class="btn btn-light mr-2 btn__close--modal">
                    Cancel
                </button>
                <button class="btn btn-light btn__close--modal ">Save</button>
            </div>
        </div>
    </div>
</template>