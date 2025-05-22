<script setup>
import CompetitionCard from '@/components/CompetitionCard.vue'

const competitions = [
    {
        title: "18th Edition - Spoken Word Contest",
        description: "Stand a chance to win the sum of 500,000",
        current: true
    },
    {
        title: "17th Edition - Spoken Word Contest [Completed]",
        description: "Stand a chance to win the sum of 500,000",
        current: false
    },
    {
        title: "16th Edition - Spoken Word Contest [Completed]",
        description: "Stand a chance to win the sum of 500,000",
        current: false
    },
    {
        title: "15th Edition - Spoken Word Contest [Completed]",
        description: "Stand a chance to win the sum of 500,000",
        current: false
    },
    {
        title: "14th Edition - Spoken Word Contest [Completed]",
        description: "Stand a chance to win the sum of 500,000",
        current: false
    },
]
</script>

<template>
    <section class="w-full max-w-2xl mx-auto px-4 sm:px-6">

        <div class="flex items-center justify-between mb-5 slide-up">
            <h1 class="text-4xl text-[var(--echo-dark-400)] barlow-condensed-bold">Competitions</h1>
            <button class="btns btn-grad bg-blue-500">Join Competition</button>
        </div>

        <div class="w-fit mx-auto mb-3">
            <CompetitionCard v-for="comp in competitions" :key="comp.title" :competition="comp" />
        </div>

        <div class="w-full flex justify-center">
            <a class="btns btn-grad bg-blue-500 slide-up mr-3">See All</a>
            <a class="btns btn-grad bg-blue-500 slide-up">View Past Winners</a>
        </div>

    </section>
</template>