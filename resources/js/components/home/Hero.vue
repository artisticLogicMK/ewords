<script setup>
import Header from '@/components/Header.vue'
</script>

<template>
    <header class="relative bg-linear-to-tl from-sky-600 to-[#000508]">
        
        <div class="relative max-w-6xl mx-auto overflow-hidden">
            <div class="absolute bottom-0 left-0 w-full h-full text-end herodown">
                <img src="/assets/handmic.png" class="absolute opacity-20 md:opacity-30 lg:opacity-100 bottom-0 right-0 inline-block w-[36rem]" />
            </div>

            <div class="relative">

                <Header class="mb-10" />

                <div class="w-full md:max-w-lg herodown px-4 sm:px-6 pb-20">
                    <div class="text-4xl md:text-5xl barlow-condensed-bold mb-5">
                        <h1 class="text-white">Unleash Your Voice!</h1>
                        <h1 class="text-blue-500">Join Our Inaugural Spoken Word Competition</h1>
                    </div>

                    <p class="text-sm md:text-base text-white mb-5">Step into the spotlight and let your words resonate. Participate in our first-ever online spoken word contest and stand a chance to win exciting prizes and be featured on our social media pages.</p>

                    <div class="flex items-center">
                        <a class="shrink-0 mr-3">
                            <button class="btns text-white border-2 border-white hover:bg-white/10">Join Competition</button>
                        </a>
                        <p class="barlow-condensed-semibold text-base text-white">Closes In: 05 Days : 12 Hours : 44 Minutes</p>
                    </div>
                </div>

            </div>
        </div>

        <img src="/assets/concave-round.svg" class="absolute bottom-0 left-0 w-full" />
    </header>
</template>