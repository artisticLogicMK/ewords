<script setup>
import { onMounted, provide } from 'vue'
import { Head } from '@inertiajs/vue3'
import initScrollAnimations from '@/lib/scrollAnimations'

import AppLayout from '@/layouts/AppLayout.vue'
import Hero from '@/components/home/Hero.vue'
import About from '@/components/home/About.vue'
import WhyJoin from '@/components/home/WhyJoin.vue'
import Competitions from '@/components/home/Competitions.vue'

const { competitions } = defineProps(['competitions'])

// Pass first competition to all child components
provide('competition', competitions[0])

onMounted(() => {
    initScrollAnimations()
})
</script>

<template>

    <Head title="Home" />
 
    <AppLayout>

        <Hero />

        <main>

            <About class="pt-30 mb-35" />

            <WhyJoin class="mb-35" />

            <Competitions :competitions="competitions" class="mb-35" />

        </main>

    </AppLayout>
</template>