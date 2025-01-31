<template>
    <div class="min-h-screen bg-black text-white p-8">
        <div class="max-w-6xl mx-auto py-20">
            <h1 class="text-6xl font-bold mb-8 text-center">Restauracje {{ city }}</h1>

            <div class="mb-8 overflow-x-auto">
                <div class="flex space-x-4 pb-2 justify-center">
                    <button
                        v-for="popularCity in popularCities"
                        :key="popularCity"
                        @click="changeCity(popularCity)"
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-full transition duration-300 ease-in-out flex-shrink-0"
                        :class="{ 'bg-blue-600 hover:bg-blue-500': city === popularCity }"
                    >
                        {{ popularCity }}
                    </button>
                </div>
            </div>

            <div v-if="loading" class="text-center text-gray-400">Ładowanie...</div>
            <div v-else-if="error" class="text-red-500">{{ error }}</div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="restaurant in restaurants" :key="restaurant.id"
                     class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                    <img :src="restaurant.image" :alt="restaurant.name" class="w-full h-48 object-cover" />
                    <div class="p-4">
                        <h2 class="text-xl font-semibold mb-2">{{ restaurant.name }}</h2>
                        <p class="text-gray-400 mb-4">{{ restaurant.address }}</p>
                        <button
                            @click="goToMenu(restaurant._id)"
                            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out"
                        >
                            Sprawdź menu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const city = ref(route.query.city || 'Warszawa')
const restaurants = ref([])
const loading = ref(true)
const error = ref(null)

const popularCities = ['Warszawa', 'Kraków', 'Gdańsk', 'Wrocław', 'Poznań', 'Łódź', 'Katowice', 'Lublin']

const changeCity = (newCity) => {
    city.value = newCity
    fetchRestaurants()
}

const goToMenu = (restaurantId) => {
    if (!restaurantId) {
        console.error("❌ Błąd: restaurantId jest undefined!", restaurantId)
        return
    }
    console.log("✅ Przekierowanie do menu restauracji:", restaurantId)
    router.push(`/restaurants/${restaurantId}/menu`)
}



const fetchRestaurants = async () => {
    loading.value = true
    error.value = null
    try {
        const response = await axios.get(`/api/restaurants?city=${city.value}`)
        console.log("Dane API:", response.data)
        restaurants.value = response.data
    } catch (err) {
        error.value = 'Nie udało się załadować listy restauracji'
    } finally {
        loading.value = false
    }
}


onMounted(fetchRestaurants)
</script>
