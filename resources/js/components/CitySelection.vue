<template>
    <div class="bg-black text-white min-h-screen p-8">
        <div class="container mx-auto max-w-6xl">
            <div class="mb-14 pt-32 flex justify-between items-center">
                <h1 class="text-4xl md:text-5xl font-bold hero-header">Odkrywaj miasta Wolt</h1>
                <ul class=" flex gap-6">
                    <li class=" underline underline-offset-2 font-medium">Polska</li>
                    <li class=" text-zinc-300/60 font-medium">Wszystkie kraje</li>
                </ul>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <div class="space-y-2">
                    <button class="w-full flex justify-between items-center p-2">
                        <div class="flex items-center gap-4">
                            <div class="w-[1.5rem] h-4">
                                <div class="w-[1.5rem] h-2 bg-white rounded-t-sm"></div>
                                <div class="w-[1.5rem] h-2 bg-red-600 rounded-b-sm"></div>
                            </div>
                            <span class="text-xl">Polska</span>
                        </div>
                    </button>

                    <button
                        class="w-full flex justify-between items-center p-2 text-blue-400 hover:text-blue-500 font-semibold"
                    >
                        Pokaż wszystkie kraje
                    </button>
                </div>

                <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template v-for="city in cities" :key="city">
                        <button
                            @click="goToRestaurants(city)"
                            class="flex justify-between items-center px-5 py-4 border-[0.8px] border-zinc-800 rounded-lg transition-colors duration-200"
                        >
                            <span class="text-xl text-white">{{ city }}</span>
                            <ChevronRight class="w-5 h-5" />
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { ChevronRight } from 'lucide-vue-next'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const cities = ref([])

onMounted(async () => {
    try {
        const response = await axios.get('/api/restaurants/cities')
        cities.value = response.data
    } catch (error) {
        console.error('Error fetching cities:', error)
    }
})

const goToRestaurants = (city) => {
    router.push({ path: '/restaurants', query: { city } })
}
</script>
