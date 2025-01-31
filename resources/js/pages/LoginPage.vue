<template>
    <div class="min-h-screen flex items-center justify-center bg-black text-white p-4">
        <div class="w-full max-w-md bg-gray-900 p-6 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold text-center mb-6">Logowanie</h1>

            <p v-if="error" class="text-red-500 text-center bg-red-900/50 p-2 rounded">{{ error }}</p>

            <form @submit.prevent="login">
                <div class="mb-4">
                    <label class="block mb-2">Email</label>
                    <input
                        v-model="email"
                        type="email"
                        class="w-full p-3 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Hasło</label>
                    <input
                        v-model="password"
                        type="password"
                        class="w-full p-3 rounded bg-gray-800 border border-gray-700 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <button type="submit"
                        :disabled="loading"
                        class="w-full bg-blue-600 hover:bg-blue-500 p-3 rounded font-bold text-white transition duration-300 ease-in-out"
                >
                    {{ loading ? 'Logowanie...' : 'Zaloguj się' }}
                </button>
            </form>

            <p class="mt-4 text-center">
                Nie masz konta? <router-link to="/register" class="text-blue-400 hover:text-blue-300">Zarejestruj się</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref(null)
const loading = ref(false)

const login = async () => {
    error.value = null
    loading.value = true

    try {
        console.log("📤 Wysyłanie danych logowania:", { email: email.value, password: password.value })

        const response = await axios.post('http://127.0.0.1:8000/api/login', {
            email: email.value,
            password: password.value
        })

        console.log("✅ Logowanie zakończone sukcesem. Odpowiedź API:", response.data)

        if (response.data.token) {
            localStorage.setItem('token', response.data.token)
            console.log("📦 Zapisany token:", response.data.token)
            const sessionId = response.data.session_id || crypto.randomUUID()
            localStorage.setItem('session_id', sessionId)

            console.log("📦 Zapisany session_id:", sessionId)

            router.push('/')
        } else {
            throw new Error("Brak tokena w odpowiedzi API.")
        }

    } catch (err) {
        console.error("❌ Błąd logowania:", err.response?.data)
        error.value = err.response?.data?.message || 'Nieprawidłowy email lub hasło.'
    } finally {
        loading.value = false
    }
}
</script>
