<template>
    <div class="relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="h-2/5 bg-black"></div>
            <div class="h-3/5 bg-[#1d1d1d]"></div>
        </div>

        <div class="relative z-10">
            <div class="container mx-auto max-w-6xl py-16">
                <div class="grid md:grid-cols-2">
                    <div class="bg-[#242424] h-[650px] flex items-center">
                        <div class="px-8 md:px-16 lg:px-28">
                            <span class="text-lg font-semibold text-white">Czym jest Wolt?</span>
                            <h2 class="text-5xl md:text-5xl lg:text-5xl font-black text-white hero-header pt-20">
                                Dostarczone.
                            </h2>
                            <p class="text-base font-medium text-gray-300 max-w-md leading-relaxed pt-10">
                                Wolt ułatwia odkrywanie i zamawianie czego tylko chcesz. Dostarczymy to do Ciebie szybko, niezawodnie i niedrogo.
                            </p>
                            <button
                                class="inline-flex items-center gap-4 group pt-10"
                                @click="playVideo"
                            >
                                <div
                                    class="w-14 h-14 rounded-full bg-blue-500 group-hover:bg-blue-600 flex items-center justify-center transition-colors"
                                >
                                    <Play class="w-5 h-5 text-black" />
                                </div>
                                <span
                                    class="text-blue-500 group-hover:text-blue-600 transition-colors text-lg"
                                >
                    Obejrzyj film
                  </span>
                            </button>
                        </div>
                    </div>

                    <div class="h-[650px]">
                        <img
                            src="/public/images/about-image.jpg"
                            alt="People enjoying Wolt delivery"
                            class="w-full h-full object-cover"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Play } from "lucide-vue-next";

const playVideo = () => {
    console.log("Play video clicked");
};
</script>

