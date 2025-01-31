<template>
    <div class="min-h-screen bg-black text-white p-4 sm:p-8 relative">
        <div class="max-w-6xl mx-auto py-12 sm:py-20">
            <h1 class="text-4xl sm:text-5xl font-bold mb-6 text-center">{{ restaurantName }}</h1>
            <p v-if="loading" class="text-center text-gray-400">
                <span class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500 mr-2"></span>
                Ładowanie menu...
            </p>
            <p v-else-if="error" class="text-center text-red-500 bg-red-900/50 p-4 rounded-lg">{{ error }}</p>

            <div v-else>
                <div v-for="category in menu" :key="category.title" class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-center text-blue-500">{{ category.title }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="item in category.items" :key="item.name"
                             class="bg-black border border-blue-700/40 rounded-lg overflow-hidden shadow-lg transition duration-300 ease-in-out hover:shadow-blue-500/30 hover:scale-105">
                            <img :src="item.photoUrl" :alt="item.name" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-xl font-semibold mb-2 text-blue-400">{{ item.name }}</h3>
                                <p class="text-gray-300 mb-4">{{ item.price }} zł</p>
                                <button
                                    @click="addToCart(item)"
                                    class="w-full bg-blue-700 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out"
                                >
                                    Dodaj do koszyka
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="cart.length > 0"
            class="fixed bottom-4 right-4 w-80 bg-black border border-blue-700 rounded-lg shadow-lg overflow-hidden transition-all duration-300 ease-in-out"
            :class="{ 'h-96': isCartOpen, 'h-16': !isCartOpen }"
        >
            <div
                @click="toggleCart"
                class="bg-blue-700 p-4 cursor-pointer flex justify-between items-center"
            >
                <h2 class="text-xl font-bold text-white">Twój koszyk ({{ cartItemsCount }})</h2>
                <span class="text-2xl">{{ isCartOpen ? '▼' : '▲' }}</span>
            </div>
            <div v-if="isCartOpen" class="p-4 overflow-y-auto h-72">
                <ul class="mb-4">
                    <li v-for="(item, index) in cart" :key="index" class="flex justify-between items-center bg-blue-700/20 p-2 rounded mb-2">
                        <span>{{ item.menu_item_id }} - {{ item.quantity }} szt. ({{ item.price * item.quantity }} zł)</span>
                        <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-400">🗑️</button>
                    </li>
                </ul>
                <p class="text-lg font-semibold text-blue-400">Łączna suma: {{ totalCartPrice }} zł</p>
                <button
                    @click="orderCart"
                    class="w-full mt-4 bg-blue-700 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out"
                >
                    Złóż zamówienie
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const restaurantId = ref(route.params.id)
const restaurantName = ref('')
const menu = ref([])
const cart = ref([])
const loading = ref(true)
const error = ref(null)
const isCartOpen = ref(false)

const fetchMenu = async () => {
    loading.value = true
    error.value = null
    try {
        const response = await axios.get(`/api/restaurants/${restaurantId.value}/menu`)
        restaurantName.value = response.data.name
        menu.value = response.data.menu
    } catch (err) {
        error.value = 'Nie udało się załadować menu. Spróbuj ponownie później.'
    } finally {
        loading.value = false
    }
}

const addToCart = (item) => {
    const existingItem = cart.value.find(i => i.menu_item_id === item.name)
    if (existingItem) {
        existingItem.quantity += 1
    } else {
        cart.value.push({ menu_item_id: item.name, quantity: 1, price: parseFloat(item.price.replace(',', '.')) })
    }
    isCartOpen.value = true
}

const removeFromCart = (index) => {
    cart.value.splice(index, 1)
}

const totalCartPrice = computed(() => {
    return cart.value.reduce((total, item) => total + item.price * item.quantity, 0).toFixed(2)
})

const cartItemsCount = computed(() => {
    return cart.value.reduce((total, item) => total + item.quantity, 0)
})

const toggleCart = () => {
    isCartOpen.value = !isCartOpen.value
}

const orderCart = async () => {
    if (cart.value.length === 0) {
        return
    }

    const token = localStorage.getItem('token')
    console.log("📦 Wysłany token w zamówieniu:", token)

    if (!token) {
        router.push('/login')
        return
    }

    const sessionId = localStorage.getItem('session_id') || crypto.randomUUID()
    localStorage.setItem('session_id', sessionId)
    console.log("🆔 Wysłany session_id:", sessionId)

    try {
        const response = await axios.post('http://127.0.0.1:8000/api/orders', {
            session_id: sessionId,
            restaurant_id: restaurantId.value,
            items: cart.value
        }, {
            headers: { Authorization: `Bearer ${token}` }
        })

        console.log("✅ Zamówienie złożone:", response.data)
        router.push(`/orders/${response.data._id}`)
    } catch (error) {
        console.error("❌ Błąd przy składaniu zamówienia:", error.response?.data)
    }
}

onMounted(fetchMenu)
</script>

