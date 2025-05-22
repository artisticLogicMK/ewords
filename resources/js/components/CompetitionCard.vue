<script setup>
defineProps(['competition'])
</script>

<template>
    <div class="flex w-full items-center border-b border-neutral-200/90 last:border-none p-5 slide-up">
        <div class="shrink-0 w-fit h-fit bg-radial from-sky-600 to-[#000508] rounded-lg mr-5">
            <img src="/assets/trophy.png" class="w-24 scale-in" alt="">
        </div>

        <div>
            <h1 class="text-2xl text-[var(--echo-dark-400)] barlow-condensed-bold">{{ competition.title }}</h1>
            <p class="text-sm text-black/70">{{ competition.description }}</p>
            <p v-if="competition?.current" class="flex items-center text-sm text-black/50">
                <div class="h-2 w-2 bg-green-500 rounded-full animate-pulse mr-2"></div> On-going
            </p>
            <div class="mt-3">
                <button v-if="competition?.current" class="btns-sm btn-grad text-black bg-blue-500 mr-4">Follow Competition</button>
                <button v-else class="btns-sm btn-grad text-black bg-blue-500 mr-4">View Winners</button>
                <button class="btns-sm btn-grad-dark text-black">Learn More</button>
            </div>
        </div>
    </div>
</template>