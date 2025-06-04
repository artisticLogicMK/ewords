<script setup>
import { Link } from '@inertiajs/vue3'

defineProps(['competition'])
</script>

<template>
    <div class="flex w-full items-start sm:items-center border-b border-neutral-200/90 last:border-none py-5 slide-up">
        <div class="shrink-0 w-20 sm:w-24 h-20 sm:h-24 bg-radial from-sky-600 to-[#000508] rounded-lg mr-3 sm:mr-5">
            <img v-if="competition.cover" :src="`/storage/${competition.cover}`" class="coverImgs fade-in" :alt="competition.title">
            <img v-else src="/assets/trophy.png" class="coverImgs scale-in">
        </div>

        <div>
            <h1 class="text-xl sm:text-2xl text-[var(--echo-dark-400)] barlow-condensed-bold">{{ competition.title }}</h1>
            <p class="text-sm text-black/70">{{ competition.description }}</p>
            <p v-if="competition?.current" class="flex items-center text-sm text-black/50">
                <span class="h-2 w-2 bg-green-500 rounded-full animate-pulse mr-2"></span> On-going
            </p>
            <div class="c-card-links mt-3 flex flex-wrap space-y-2">
                <Link v-if="competition?.current" :href="`/competitions/${competition.slug}`" class="btns-sm btn-grad text-black bg-blue-500 mr-4">Follow&nbsp;Competition</Link>
                <Link v-else :href="`/competitions/${competition.slug}/#winners`" class="btns-sm btn-grad text-black bg-blue-500 mr-4">View Winners</Link>
                <Link v-if="competition?.current" :href="`/competitions/${competition.slug}/contestants`" class="btns-sm btn-grad-dark text-black">Contestants</Link>
                <Link v-else :href="`/competitions/${competition.slug}`" class="btns-sm btn-grad-dark text-black">Learn More</Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
@reference "@/css/app.css";

.coverImgs { @apply w-full h-full }

.c-card-links a { @apply h-fit }
</style>