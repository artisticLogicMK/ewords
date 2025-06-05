<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'
import initScrollAnimations from '@/lib/scrollAnimations'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesHeader from '@/components/PagesHeader.vue'
import ContestantsCard from '@/components/ContestantsCard.vue'
import Countdowns from '@/components/Countdowns.vue'
import { PhInfo } from '@phosphor-icons/vue'

const { competition } = defineProps(['competition'])

const breadcumb = [
    { name: "Home", url: "/" },
    { name: "Competitions", url: "/competitions" },
    { name: competition.title, url: `/competitions/${competition.slug}` }
]

const contestants = [
  { name: "Okafor Augustina Chigozie", pic: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS2ry4hDUEb2vxQAGRDTRor5jdAkvlIg28V8NRDx-MhXYqAiOkiXh9kynwp&s=10", votes: 27 },
  { name: "Christabel Bassey", pic: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcScdAHPWjx0V3E3J4ZYdc13QHxJ8T8vww6plPK2LC4xEdvr0fODS1jlWK8&s=10", votes: 11 },
  { name: "Okafor Augustina Chigozie", pic: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQqKU7bcz1k1Ks7A6oF2I63_OYkOc11hXlP9g&usqp=CAU", votes: 6 },
]

onMounted(() => {
    initScrollAnimations()
})
</script>

<template>
    <Head :title="competition.title" />

    <AppLayout>

        <PagesHeader
            :title="competition.title"
            :breadcumb="breadcumb"
            class="mb-25 scale-in"
            :image="competition.cover"
        />

        <main class="w-full max-w-2xl mx-auto mb-30 px-4 sm:px-6">

          <p class="mb-5 text-blue-500 text-center">
              <Countdowns :competition="competition" />
          </p>
          
          <div class="flex justify-center mb-3">
            <div class="w-fit h-fit grad rounded-md overflow-hidden">
              <img :src="competition.cover ? `/storage/${competition.cover}` : '/assets/trophy.png'" class="w-45 sm:w-50 scale-in" :alt="competition.title" />
            </div>
          </div>


          <h1 class="text-2xl sm:text-3xl text-[var(--echo-dark-400)] barlow-condensed-bold mb-5 text-center">
            {{ competition.title }}
          </h1>
  
  
          <div class="w-full mb-3 torch-doc border-b bdr pb-5">
            {{ competition.content }}
          </div>

          <div>
            <div class="flex items-center justify-between mb-3">
              <h1 class="text-2xl text-[var(--echo-dark-400)] barlow-condensed-bold">Contestants</h1>
              <Link class="btns-sm btn-grad">Refresh</Link>
            </div>
            
            <p class="bg-sky-100 border border-sky-200 text-black/90 rounded-lg p-3 text-sm mb-5">
              <PhInfo weight="fill" class="text-lg inline-block" /> As of today, Friday, 30 May 2025, 03:09pm, the contestants listed below are the Top Contestants (by votes). Voting is still on. Keep voting to increase the position of your favorite contestants. The Top 3 Contestants wins the prize.
            </p>
            
            <div class="sm:grid grid-cols-2 gap-4 mb-5">
              <ContestantsCard v-for="c in contestants" :key="c.name" :contestant="c" />
            </div>
            
            <div class="w-full flex justify-center">
                <Link class="btns btn-grad bg-blue-500 slide-up">View All Contestants</Link>
            </div>
            
          </div>

        </main>

    </AppLayout>
</template>