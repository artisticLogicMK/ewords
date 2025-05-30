<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { onMounted } from 'vue'
import initScrollAnimations from '@/lib/scrollAnimations'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesHeader from '@/components/PagesHeader.vue'
import ContestantsCard from '@/components/ContestantsCard.vue'

const breadcumb = [
    { name: "Home", url: "/" },
    { name: "Competitions", url: "/competitions" },
    { name: "18th Spoken Words Contest", url: "/competitions" }
]

constestants = [
  { name: "Okafor Augustina Chigozie", pic: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS2ry4hDUEb2vxQAGRDTRor5jdAkvlIg28V8NRDx-MhXYqAiOkiXh9kynwp&s=10", votes: 27 },
  { name: "Christabel Bassey", pic: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcScdAHPWjx0V3E3J4ZYdc13QHxJ8T8vww6plPK2LC4xEdvr0fODS1jlWK8&s=10", votes: 11 },
  { name: "Okafor Augustina Chigozie", pic: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQqKU7bcz1k1Ks7A6oF2I63_OYkOc11hXlP9g&usqp=CAU", votes: 6 },
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
        />

        <main class="w-full max-w-2xl mx-auto mb-30 px-4 sm:px-6">
          
          <div class="flex justify-center mb-3">
            <div class="w-fit h-fit grad rounded-md overflow-hidden">
              <img src="/assets/trophy.png" class="w-48" alt="" />
            </div>
          </div>


          <h1 class="text-4xl text-[var(--echo-dark-400)] barlow-condensed-bold mb-3 text-center slide-up">18th Spoken Words Contest</h1>
  
  
          <div class="w-full mb-3 torch-doc border-b bdr pb-3">
              Spoken Word Contest is a Talent competition organised by Sageatwords.com.<br><br>
- Registration ended at exactly 09:00pm on the May 9th 2025.<br>
- Voting started at exactly 12:00am on the May 10th 2025.<br>
- Voting was activated at exactly 12:00am on the May 10th 2025.<br>
- Voting for the 1st Stage will end May 16th 2025.<br>
- Votes for the 1st Stage determine those going into the 2nd Stage. And votes for the 2nd Stage determine the Winners. Which means everyone's votes will start afresh.<br>
- The 1st Stage ended by 11:59 pm, May 16th 2025. Only the Top 30 Contestants (by votes) qualified.<br>
- The 2nd Stage starts immediately on the May 17th 2025.<br>
- Voting for the 2nd Stage will be activated by 7:00 am, on May 17th 2025.<br>
- Voting for the 2nd Stage will officially start by 7:00 am on May 17th 2025.<br>
- The 2nd Stage is the Final Stage.<br>
- The 2nd Stage ends at 11:59 pm, May 23rd 2025.<br>
- The Top 3 Contestants win the prize.<br><br>
<strong>Prizes</strong>
- 1st Prize: N500,000 + Golden-Luxe Award Plaque + 2 Years Long Supply of Premium Ballpoint Pens.<br>
- 2nd Prize: N150,000<br>
- 3rd Prize: N50,000<br>
For more info and support, email support@sageatwords.com<br><br>
This competition is sponsored by TheNextStar, TheMostCreative, and SageAtWords.
          </div>

          <div>
            <div class="flex items-center justify-between mb-3">
              <h1 class="text-2xl text-[var(--echo-dark-400)] barlow-condensed-bold">Contestants</h1>
              <Link class="btns-sm btn-grad">Refresh</Link>
            </div>
            
            <p class="bg-sky-100 border border-sky-200 rounded-lg p-3 text-sm">As of today, Friday, 30 May 2025, 03:09pm, the contestants listed below are the Top Contestants (by votes). Voting is still on. Keep voting to increase the position of your favorite contestants. The Top 3 Contestants wins the prize.</p>
            
            <div class="mb-3">
              <ContestantsCard v-for="c in contestants" :key="c.name" :contestant="c" />
            </div>
            
            <div class="w-full flex justify-center slide-up">
                <Link class="btns btn-grad bg-blue-500 slide-up">View All Contestants</Link>
            </div>
            
          </div>

        </main>

    </AppLayout>
</template>