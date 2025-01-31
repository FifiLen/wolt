<template>
    <div class="h-screen bg-[#202125] text-white p-4 flex items-center justify-center">
        <div class="max-w-6xl w-full bg-[#2B2B30] rounded-xl shadow-2xl overflow-hidden">
            <div class="p-6 flex flex-col h-full">
                <h1 class="text-2xl font-bold mb-6 text-center text-white">
                    Szczegóły zamówienia
                </h1>

                <div v-if="loading" class="flex-grow flex justify-center items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#00C4B4]"></div>
                </div>

                <div v-else-if="error" class="flex-grow flex items-center justify-center">
                    <div class="text-center text-red-500 bg-red-900/20 p-4 rounded-lg">
                        <p class="text-xl font-semibold mb-2">Wystąpił błąd</p>
                        <p>{{ error }}</p>
                    </div>
                </div>

                <div v-else class="flex-grow flex flex-col space-y-4 overflow-hidden">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">Zamówienie #{{ order._id.slice(-6).toUpperCase() }}</h2>
                        <span class="px-3 py-1 rounded-full text-sm font-medium"
                              :class="{
                                'bg-[#00C4B4] text-black': order.status === 'pending',
                                'bg-green-500 text-black': order.status === 'completed',
                                'bg-red-500 text-white': order.status === 'cancelled'
                              }">
                            {{ getStatusText(order.status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-[#202125] p-4 rounded-lg">
                            <p class="text-gray-400 mb-1">Restauracja</p>
                            <p class="font-medium">{{ restaurantName }}</p>
                        </div>
                        <div class="bg-[#202125] p-4 rounded-lg">
                            <p class="text-gray-400 mb-1">Łączna cena</p>
                            <p class="font-medium">{{ order.total_price }} zł</p>
                        </div>
                    </div>

                    <div class="flex-grow overflow-y-auto">
                        <h3 class="text-lg font-semibold mb-2">Zamówione pozycje</h3>
                        <ul class="space-y-2">
                            <li v-for="(item, index) in order.items" :key="item.menu_item_id"
                                class="flex justify-between items-center bg-[#202125] p-4 rounded-lg">
                                <span class="font-medium">{{ item.menu_item_id }}</span>
                                <span class="text-[#00C4B4]">{{ item.quantity }} szt.</span>
                            </li>
                        </ul>
                    </div>

                    <button v-if="order.status === 'pending'"
                            @click="cancelOrder"
                            class="w-full bg-[#00A4B4] hover:bg-[#00C4B4] text-white font-medium py-3 px-4 rounded-lg transition duration-300 ease-in-out"
                    >
                        Anuluj zamówienie
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();

const orderId = ref(route.params.id);
const order = ref(null);
const restaurantName = ref('Ładowanie...');
const loading = ref(true);
const error = ref(null);

const getStatusText = (status) => {
    switch (status) {
        case 'pending': return 'W trakcie';
        case 'completed': return 'Zrealizowane';
        case 'cancelled': return 'Anulowane';
        default: return status;
    }
};

const fetchOrder = async () => {
    loading.value = true;
    error.value = null;
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get(`http://127.0.0.1:8000/api/orders/${orderId.value}`, {
            headers: { Authorization: `Bearer ${token}` }
        });
        order.value = response.data;

        fetchRestaurantName(order.value.restaurant_id);
    } catch (err) {
        console.error("❌ Błąd przy pobieraniu zamówienia:", err.response?.data);
        error.value = "Nie udało się załadować zamówienia. Sprawdź połączenie.";
    } finally {
        loading.value = false;
    }
};

const fetchRestaurantName = async (restaurantId) => {
    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/restaurants/${restaurantId}`);
        restaurantName.value = response.data.name;
    } catch (err) {
        console.error("❌ Nie udało się pobrać nazwy restauracji:", err.response?.data);
        restaurantName.value = "Nieznana restauracja";
    }
};

const cancelOrder = async () => {
    if (!confirm("Czy na pewno chcesz anulować to zamówienie?")) return;
    try {
        const token = localStorage.getItem('token');
        await axios.delete(`http://127.0.0.1:8000/api/orders/${orderId.value}`, {
            headers: { Authorization: `Bearer ${token}` }
        });
        alert("Zamówienie zostało anulowane.");
        router.push('/');
    } catch (err) {
        alert("Nie udało się anulować zamówienia. Spróbuj ponownie.");
    }
};

onMounted(fetchOrder);
</script>
