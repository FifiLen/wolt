<template>
    <div class="p-8 text-white">
        <h1 class="text-3xl font-bold mb-6">Twoje zamówienia</h1>

        <div v-if="loading" class="text-gray-400">Ładowanie...</div>
        <div v-else-if="error" class="text-red-500">{{ error }}</div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="order in orders" :key="order.id"
                 class="p-4 border border-gray-700 rounded-lg">
                <h2 class="text-xl font-semibold">Zamówienie #{{ order.id }}</h2>
                <p class="text-gray-400">Status: {{ order.status }}</p>
                <button
                    @click="goToOrder(order.id)"
                    class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-full text-white"
                >
                    Szczegóły
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const orders = ref([])
const loading = ref(true)
const error = ref(null)

const goToOrder = (id) => {
    router.push(`/orders/${id}`)
}

onMounted(async () => {
    try {
        const response = await axios.get('/api/orders', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`
            }
        })
        orders.value = response.data
    } catch (err) {
        error.value = 'Nie udało się załadować zamówień'
    } finally {
        loading.value = false
    }
})
</script>
