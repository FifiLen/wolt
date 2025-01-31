<template>
    <div class="register-container">
        <h2>Rejestracja</h2>
        <form @submit.prevent="register">
            <div>
                <label for="name">Imię</label>
                <input type="text" id="name" v-model="form.name" required />
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" v-model="form.email" required />
            </div>
            <div>
                <label for="password">Hasło</label>
                <input type="password" id="password" v-model="form.password" required />
            </div>
            <div>
                <label for="password_confirmation">Potwierdź hasło</label>
                <input type="password" id="password_confirmation" v-model="form.password_confirmation" required />
            </div>
            <button type="submit" :disabled="loading">
                {{ loading ? 'Rejestrowanie...' : 'Zarejestruj się' }}
            </button>
            <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const loading = ref(false)
const errorMessage = ref('')
const router = useRouter()

const register = async () => {
    loading.value = true
    errorMessage.value = ''

    try {
        console.log("⏳ Wysyłanie danych rejestracji:", form.value);

        const response = await axios.post('http://127.0.0.1:8000/api/register', form.value);

        console.log("✅ Rejestracja zakończona sukcesem. Odpowiedź API:", response.data);

        if (response.data.token) {
            console.log("📝 Otrzymany token:", response.data.token);
            localStorage.setItem('token', response.data.token);
            console.log("📂 Zapisany token w localStorage:", localStorage.getItem('token'));

        } else {
            console.error("❌ Brak tokena w odpowiedzi!", response.data);
        }
    } catch (error) {
        console.error("⚠️ Błąd rejestracji:", error.response?.data);
        errorMessage.value = error.response?.data?.message || 'Wystąpił błąd rejestracji';
    } finally {
        loading.value = false;
    }
}
</script>
