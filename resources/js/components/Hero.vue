<template>
    <div
        style="background-image: url('/images/wolt.jpg')"
        class="relative h-[63vh] bg-[#cbe2ae] overflow-hidden bg-right-top bg-no-repeat"
    >
        <div class="container max-w-6xl mx-auto py-12 md:py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="space-y-6">
                    <h1 class="text-4xl md:text-5xl lg:text-7xl font-black text-black hero-header">
                        Weekend na
                        <br />
                        chillu?
                    </h1>

                    <div class="space-y-4 max-w-md">
                        <div class="relative w-full">
                <span class="absolute inset-y-0 left-3 flex items-center text-zinc-400/90">
                  <MapPin class="w-7 h-7" />
                </span>
                            <input
                                type="text"
                                placeholder="Wpisz adres dostawy"
                                class="w-full pl-12 py-4 text-lg bg-black text-white placeholder:text-zinc-400/90 placeholder:text-base placeholder:font-semibold placeholder:tracking-tight rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>

                        <div class="space-y-2">
                            <button
                                @click="handleLocationShare"
                                class="text-black font-semibold tracking-tight hover:underline flex items-center gap-2 underline underline-offset-3"
                            >
                                <LocateFixed class="w-4 h-4" />
                                Udostępnij swoją lokalizację
                            </button>
                            <router-link to="/login" class="text-black hover:underline block font-semibold tracking-tight underline underline-offset-3">
                                Zaloguj się, aby wybrać zapisany adres
                            </router-link>
                        </div>
                    </div>
                </div>


                <div class="relative h-[400px] md:h-[500px]">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-[#e5ecd6] via-transparent to-transparent md:hidden"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { MapPin, LocateFixed } from 'lucide-vue-next'
import { RouterLink } from 'vue-router';

const handleLocationShare = () => {
    alert("Udostępnianie lokalizacji jeszcze nie działa!")
}
</script>
