<template>
    <div class="relative bg-[#f2f3f5] overflow-hidden">
        <div class="container max-w-6xl mx-auto px-4 py-16 md:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div class="space-y-10">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 hero-header">
                        Kochanie, dzisiaj nie
                        <br />
                        gotujemy
                    </h1>
                    <p class="text-lg text-gray-600 max-w-lg">
                        Pobierz nagrodzoną przez Apple aplikację Wolt i wybieraj spośród ponad 40,000 restauracji i setek sklepów w ponad 20 krajach. Odkrywaj i zamawiaj co tylko chcesz - a kurierzy Ci to dostarczą.
                    </p>

                    <div class="flex space-x-4">
                        <a href="#" class="inline-block">
                            <img
                                src="/public/images/apple.svg"
                                alt="Pobierz w App Store"
                                class="h-10 w-auto"
                            />
                        </a>
                        <a href="#" class="inline-block">
                            <img
                                src="/public/images/google.svg"
                                alt="Pobierz z Google Play"
                                class="h-10 w-auto"
                            />
                        </a>
                    </div>
                </div>

                <div
                    class="relative h-[600px] bg-right bg-no-repeat bg-cover"
                    style="background-image: url('/images/download.png')"
                >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

</script>
