<template>
    <div class="p-8 text-white">
        <h1 class="text-3xl font-bold mb-6">Szczegóły zamówienia</h1>

        <div v-if="loading" class="text-gray-400">Ładowanie...</div>
        <div v-else-if="error" class="text-red-500">{{ error }}</div>

        <div v-else class="p-6 border border-gray-700 rounded-lg">
            <p class="text-xl">ID Zamówienia: {{ order.id }}</p>
            <p class="text-gray-400">Status: {{ order.status }}</p>
            <h2 class="text-2xl font-bold mt-4">Pozycje:</h2>
            <ul>
                <li v-for="item in order.items" :key="item.menu_item_id">
                    {{ item.menu_item_id }} (x{{ item.quantity }})
                </li>
            </ul>
            <p class="text-xl mt-4 font-bold">Całkowita cena: {{ order.total_price }} zł</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const order = ref(null)
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
    try {
        const response = await axios.get(`/api/orders/${route.params.id}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`
            }
        })
        order.value = response.data
    } catch (err) {
        error.value = 'Nie udało się załadować szczegółów zamówienia'
    } finally {
        loading.value = false
    }
})
</script>
