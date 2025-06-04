<script setup>
import { inject } from 'vue'
import { Link } from '@inertiajs/vue3'
import Header from '@/components/Header.vue'
import Countdowns from '@/components/Countdowns.vue'

const competition = inject('competition')
</script>

<template>
    <header class="relative bg-linear-to-tl from-sky-600 to-[#000508]">

        <div class="relative max-w-6xl mx-auto overflow-hidden">
            <div class="absolute bottom-0 left-0 w-full h-full text-end herodown">
                <img src="/assets/handmic.png" class="absolute opacity-20 md:opacity-30 lg:opacity-100 bottom-0 right-0 inline-block w-[36rem]" />
            </div>

            <div class="relative">

                <Header class="mb-10" />

                <div class="w-full heroup px-4 sm:px-6 pb-20" data-start="top -30%">
                    <div class="w-full md:max-w-lg text-4xl md:text-5xl barlow-condensed-bold mb-5">
                        <h1 class="text-white">Unleash Your Voice!</h1>
                        <h1 class="text-blue-500">Join Our Inaugural Spoken Word Competition</h1>
                    </div>

                    <p class="w-full md:max-w-lg text-sm md:text-base text-white mb-5">Step into the spotlight and let your words resonate. Participate in our first-ever online spoken word contest and stand a chance to win exciting prizes and be featured on our social media pages.</p>

                    <div class="flex flex-wrap items-center">
                        <Link
                            :href="competition ? `/competitions/${competition.slug}/contest` : '#'"
                            class="herojoin btns text-white border-2 border-white bg-transparent shrink-0 mr-3 mb-3"
                        >Join Competition</Link>
        
                        <span class="mb-3 text-white">
                            <Countdowns v-if="competition" :competition="competition" />
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <img src="/assets/concave-round.svg" class="absolute bottom-0 left-0 w-full" />
    </header>
</template>

<style scoped>
@reference "@/css/app.css";

.herojoin:hover { background: rgba(255, 255, 255, 0.15)  }
</style>