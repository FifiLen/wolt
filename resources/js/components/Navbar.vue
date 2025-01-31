<template>
    <div class="w-full bg-black">
        <nav class="bg-black text-white py-3 max-w-6xl mx-auto flex items-center justify-between">
            <div>
                <p><router-link to="/" class="text-4xl tracking-tight font-bold logo">Wolt</router-link></p>
            </div>
            <ul class="flex space-x-4 items-center">
                <li v-if="isLoggedIn">
                    <button @click="logout"
                            class="order-last font-semibold tracking-tight hover:text-gray-200">
                        Wyloguj
                    </button>
                </li>
                <li v-else>
                    <router-link to="/login"
                                 class="order-last font-semibold tracking-tight hover:text-gray-200">
                        Zaloguj
                    </router-link>
                </li>
                <li v-if="!isLoggedIn">
                    <router-link to="/register"
                                 class="hover:bg-blue-400/30 order-last px-4 py-3 bg-blue-600/20 text-blue-400 rounded-lg flex items-center font-semibold tracking-tight">
                        Zarejestruj się
                    </router-link>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const isLoggedIn = ref(false)
const router = useRouter()

const checkAuth = () => {
    const token = localStorage.getItem('token')
    isLoggedIn.value = !!token
}

const logout = () => {
    localStorage.removeItem('token')
    localStorage.removeItem('session_id')
    isLoggedIn.value = false
    router.push('/login')
}

onMounted(checkAuth)
</script>
