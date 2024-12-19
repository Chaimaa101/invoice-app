<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.withCredentials = true;

let form = ref([])
let customers = ref([])
let customer_id = ref([])
let item = ref([])
let listCart = ref([])
let listProducts = ref([])
const showModal = ref(false)
const hideModal = ref(true)

onMounted(async () => {
    newInvoice()
    getCustomers()
    getProducts()
})

const addCart = (item) => {
    const itemCart = {
        id: item.id,
        item_code: item.item_code,
        description: item.description,
        price: item.price,
        quantity: item.quantity
    }
    listCart.value.push(itemCart)
    closeModal()
}

const newInvoice = async () => {
    try {
        const response = await axios.get('/invoices/create')
        form.value = response.data
    } catch (error) {
        console.error('Error fetching form infos:', error);
    }
}
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
const removeItem = (i) => {
    listCart.value.splice(i, 1)
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
        return subTotal() - form.value.discount

}

const onSave = async () => {
    if (listCart.value.length >= 1) {
        // Calculate subtotal and total
        let subtotal = subTotal();
        let total = Total();

        // Create FormData and append values
        const formData = {
        'invoice_item' :JSON.stringify(listCart),
        'customer_id': customer_id.value,
        'date': form.value.date,
        'due_date': form.value.due_date,
        'number': form.value.number,
        'reference': form.value.reference,
        'discount': form.value.discount,
        'sub_total': subtotal,
        'total': total,
        'terms_and_conditions': form.value.terms_and_conditions,
        }
        // Try posting the data
        try {
            console.log('DATA',formData)
            await axios.post('/invoices', formData);
            listCart.value = [];  // Clear the cart after saving
            router.push('/');  // Redirect to home page
        } catch (error) {
            console.log('Error adding invoice', error);
            // Optionally, display an error message or handle errors here
        }
    } else {
        console.log("Cart is empty, cannot save invoice.");
    }
};


</script>
<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">New Invoice</h2>
                </div>
                <div>

                </div>
            </div>

            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="customer_id">
                            <option disabled>Select customer</option>
                            <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                {{ customer.firstname }}</option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p>
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date"> <!---->
                        <p class="my-1">Due Date</p>
                        <input id="due_date" type="date" class="input" v-model="form.due_date">
                    </div>
                    <div>
                        <p class="my-1">Numero</p>
                        <input type="text" class="input" v-model="form.number">
                        <p class="my-1">Reference(Optional)</p>
                        <input type="text" class="input" v-model="form.reference">
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
                    <div class="table--items2" v-for="(itemCart, i) in listCart" :key="itemCart.id">
                        <p>#{{ itemCart.item_code }} {{ itemCart.description }} </p>
                        <p>
                            <input type="text" class="input" v-model="itemCart.unit_price">
                        </p>
                        <p>
                            <input type="text" class="input" v-model="itemCart.quantity">
                        </p>
                        <p v-if="itemCart.quantity">
                            {{ (itemCart.quantity) * (itemCart.unit_price) }} Dh
                        </p>
                        <p v-else></p>
                        <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeItem(i)">
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
                        <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions"></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>{{subTotal()}} Dh</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount %</p>
                            <input type="text" class="input" > 
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
                    <a class="btn btn-secondary" @click="onSave()">
                        Save
                    </a>
                </div>
            </div>

        </div>
        <!--==================== add modal items ====================-->
        <div class="modal main__modal " :class="{ show: showModal }">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModal()">×</span>
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
                    <button class="btn btn-light mr-2 btn__close--modal" @click="closeModal()">
                        Cancel
                    </button>
                    <button class="btn btn-light btn__close--modal ">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>