<script setup>
import { Link, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesHeader from '@/components/PagesHeader.vue'
import WinnersCard from '@/components/WinnersCard.vue'

const { competitions } = defineProps(['competitions'])

const breadcumb = [
    { name: "Home", url: "/" },
    { name: "Past Winners", url: "/pastwinners" }
]
</script>


<template>

    <Head title="Past Winners" />

    <AppLayout>

        <PagesHeader
            title="Past Winners"
            :breadcumb="breadcumb"
            class="mb-20 scale-in"
        />

        <main v-if="competitions.length" class="w-full max-w-3xl mx-auto mb-30 px-4 sm:px-6">


          <h1 class="text-2xl sm:text-3xl text-[var(--echo-dark-400)] barlow-condensed-bold mb-5 text-center">
            {{ competitions.data[0].title }}
          </h1>
          <p class="text-sm sm:text-base text-neutral-600 mb-5">
            These are the winners of the recently concluded {{ competitions.data[0].title }}.
          </p>

            
            <div class="sm:grid grid-cols-2 md:grid-cols-3 gap-4 border-b border-neutral-400/80 pb-7 mb-5">

              <WinnersCard :name="competitions.data[0].winner" :picture="competitions.data[0].winner_pic" position="Winner" emoji="🥇" />
              <WinnersCard :name="competitions.data[0].first_runner" :picture="competitions.data[0].first_runner_pic" position="1st Runner Up" emoji="🥈" />
              <WinnersCard :name="competitions.data[0].second_runner" :picture="competitions.data[0].second_runner_pic" position="2nd Runner Up" emoji="🥉" />

            </div>


          <div class="w-full torch-doc pb-5 mb-5" v-html="competitions.data[0].past_winners_content"></div>


          <div class="flex justify-center">
            <Link :href="competitions.next_page_url" class="btns btn-grad bg-blue-500">View More Past Winners</Link>
          </div>


        </main>


        <div v-else class="mb-25">
          <h1 class="text-2xl sm:text-3xl font-bold text-neutral-400 text-center">No Competitions Concluded Yet!</h1>
        </div>

    </AppLayout>
</template>