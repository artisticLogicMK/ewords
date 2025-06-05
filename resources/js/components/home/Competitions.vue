<script setup>
import { Link } from '@inertiajs/vue3'
import CompetitionCard from '@/components/CompetitionCard.vue'

const { competitions } = defineProps(['competitions'])
</script>

<template>
    <section v-if="competitions.length" class="w-full max-w-2xl mx-auto px-4 sm:px-6">

        <div class="flex items-center justify-between mb-5 slide-up">
            <h1 class="text-2xl sm:text-4xl text-[var(--echo-dark-400)] barlow-condensed-bold">Competitions</h1>
            <Link :href="`/competitions/${competitions[0].slug}/join`"  class="btns btn-grad bg-blue-500">Join</Link>
        </div>

        <div class="w-full max-w-fit mx-auto mb-4">
            <CompetitionCard
                v-for="(comp, i) in competitions"
                :key="comp.slug"
                :competition="{ ...comp, current: i === 0 }"
            />
        </div>

        <div class="w-full flex flex-wrap space-y-3 justify-center">
            <Link href="/competitions" class="btns btn-grad bg-blue-500 h-fit slide-up mr-3">See All</Link>
            <Link href="/past-winners" class="btns btn-grad h-fit bg-blue-500 slide-up">View Past Winners</Link>
        </div>

    </section>
</template>