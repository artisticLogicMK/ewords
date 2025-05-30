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
            class="mb-25 scale-in"
            :image="'/assets/mesh.png'"
        />

        <main class="w-full max-w-2xl mx-auto mb-30 px-4 sm:px-6">

            <h1 class="text-4xl text-[var(--echo-dark-400)] barlow-condensed-bold mb-3 text-center slide-up">Competitions</h1>

            <div class="w-fit mx-auto mb-3">
                <CompetitionCard v-for="comp in competitions" :key="comp.title" :competition="comp" />
            </div>

            <div class="pagination mb-3 slide-up">
                <p>Viewing 10 of 26</p>

                <div class="page-links">
                    <Link>Prev</Link>
                    <Link>Next</Link>
                </div>
            </div>

            <div class="w-full flex justify-center slide-up">
                <a class="btns btn-grad bg-blue-500 slide-up">View Past Winners</a>
            </div>

        </main>

    </AppLayout>
</template>