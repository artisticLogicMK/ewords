<script setup>
import { onMounted } from 'vue'
import initScrollAnimations from '@/lib/scrollAnimations'
import { Head } from '@inertiajs/vue3'

import AppLayout from '@/layouts/AppLayout.vue'
import Hero from '@/components/home/Hero.vue'
import About from '@/components/home/About.vue'
import WhyJoin from '@/components/home/WhyJoin.vue'
import Competitions from '@/components/home/Competitions.vue'

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

            <Competitions class="mb-35" />

        </main>

    </AppLayout>
</template>