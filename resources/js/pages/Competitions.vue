<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'
import initScrollAnimations from '@/lib/scrollAnimations'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesHeader from '@/components/PagesHeader.vue'
import CompetitionCard from '@/components/CompetitionCard.vue'

const breadcumb = [
    { name: "Home", url: "/" },
    { name: "Competitions", url: "/competitions" }
]

const { competitions } = defineProps(['competitions'])

onMounted(() => {
    initScrollAnimations()
})
</script>

<template>
    <Head title="Competitions" />

    <AppLayout>

        <PagesHeader
            title="Competitions"
            :breadcumb="breadcumb"
            class="mb-25"
        />

        <main class="w-full max-w-2xl mx-auto mb-30 px-4 sm:px-6">

            <h1 class="text-4xl text-[var(--echo-dark-400)] barlow-condensed-bold mb-3 text-center slide-up">Competitions</h1>

            <div v-if="competitions.data.length" class="w-fit mx-auto mb-3">
                <CompetitionCard
                    v-for="(comp, i) in competitions.data"
                    :key="comp.slug"
                    :competition="{ ...comp, current: i === 0 }"
                />
            </div>

            <div v-else class="text-center text-neutral-300 text-2xl font-bold mt-8">
                🏆 No Competitions yet...
            </div>

            <div class="pagination mb-4 slide-up">
                <p>Page {{ competitions.current_page }} of {{ competitions.last_page }}</p>
                <div class="page-links">
                    <Link :href="competitions.prev_page_url" :class="{'pointer-events-none opacity-50': !competitions.prev_page_url}">Prev</Link>
                    <Link :href="competitions.next_page_url" :class="{'pointer-events-none opacity-50': !competitions.next_page_url}">Next</Link>
                </div>
            </div>

            <div class="w-full flex justify-center slide-up">
                <Link href="/past-winners" class="btns btn-grad bg-blue-500 slide-up">View Past Winners</Link>
            </div>

        </main>

    </AppLayout>
</template>