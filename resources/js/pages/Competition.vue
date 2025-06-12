<script setup>
import { Link, router, Head } from '@inertiajs/vue3'
import { onMounted } from 'vue'
import initScrollAnimations from '@/lib/scrollAnimations'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesHeader from '@/components/PagesHeader.vue'
import ContestantsCard from '@/components/ContestantsCard.vue'
import WinnersCard from '@/components/WinnersCard.vue'
import Countdowns from '@/components/Countdowns.vue'
import { PhInfo } from '@phosphor-icons/vue'
import moment from 'moment-timezone'

const { competition, contestants } = defineProps(['competition', 'contestants'])

const refresh = () => {
  router.reload({ preserveScroll: true, preserveState: true })
}

const breadcumb = [
    { name: "Home", url: "/" },
    { name: "Competitions", url: "/competitions" },
    { name: competition.title, url: `/competitions/${competition.slug}` }
]

onMounted(() => {
    initScrollAnimations()
})
</script>

<template>
    
    <Head :title="`Join the ${competition.title}`" />

    <AppLayout>

        <PagesHeader
            :title="competition.title"
            :breadcumb="breadcumb"
            class="mb-20 scale-in"
            :image="competition.cover"
        />

        <main class="w-full max-w-2xl mx-auto mb-30 px-4 sm:px-6">


          <div v-if="$page.props.flash.success" class="alerts success-alert mb-5">{{ $page.props.flash.success }}</div>
          <div v-if="$page.props.flash.error" class="alerts error-alert mb-5">{{ $page.props.flash.error }}</div>

          
          <Countdowns :competition="competition" class="countdown" />
          

          <div class="flex justify-center mb-5">
            <div class="w-fit h-fit grad rounded-lg overflow-hidden">
              <img :src="competition.cover ? `/storage/${competition.cover}` : '/assets/trophy.png'"
                class="w-45 h-45 sm:w-50 sm:h-50 object-cover object-center border border-neutral-200" :alt="competition.title" />
            </div>
          </div>



          <h1 class="text-2xl sm:text-3xl text-[var(--echo-dark-400)] barlow-condensed-bold mb-5 text-center">
            {{ competition.title }}
          </h1>
  
  
          <div class="w-full mb-3 torch-doc border-b bdr pb-5" v-html="competition.content"></div>


          <template v-if="competition.set_winners === 1 && competition.active === 0 && competition.registration_active === 0 && competition.voting_active === 0">
            <h1 class="text-xl sm:text-2xl blw-semibold text-[var(--echo-dark-400)] text-center mt-5">Winners of the {{ competition.title }}.</h1>
            <div class="sm:grid grid-cols-1 gap-4 mb-5 mt-5">

              <WinnersCard :name="competition.winner" :picture="competition.winner_pic" position="Winner" emoji="🥇" />
              <WinnersCard :name="competition.first_runner" :picture="competition.first_runner_pic" position="1st Runner Up" emoji="🥈" />
              <WinnersCard :name="competition.second_runner" :picture="competition.second_runner_pic" position="2nd Runner Up" emoji="🥉" />

            </div>
          </template>



          <div v-else>
            <div class="flex items-end justify-between mb-5">
              <h1 class="text-xl sm:text-2xl text-[var(--echo-dark-400)] barlow-condensed-bold">Contestants</h1>
              <div class="space-x-3">
                <button @click="refresh" class="btns-sm btn-grad">Refresh</button>
                <Link v-if="competition.registration_active" :href="`/competitions/${competition.slug}/join`" class="btns-sm btn-grad">Join</Link>
              </div>
            </div>
            
            <p class="bg-sky-100 border border-sky-200 text-black/90 rounded-lg p-3 text-sm mb-5">
              <PhInfo weight="fill" class="text-lg inline-block" /> As of today, {{  moment().format('ddd MMM D, YYYY h:mm a') }}, the contestants listed below are the Top Contestants (by votes). {{ competition.voting_active === 1 ? 'Voting is still on. Keep voting to increase the position of your favorite contestants.' : ''}} The Top 3 Contestants wins the prize.
            </p>
            
            <div v-if="contestants.length" class="sm:grid grid-cols-2 gap-4 mb-5">
              <ContestantsCard
                v-for="c in contestants"
                :key="c.name"
                :contestant="c"
                :isVoting="competition.voting_active === 1 && competition.registration_active === 0"
                :competitionSlug="competition.slug" />
            </div>

            <div v-else class="none mt-8">🏆 No Contestants Yet...</div>
            
            <div v-if="contestants.length" class="w-full flex justify-center">
                <Link :href="`/competitions/${competition.slug}/contestants`" class="btns btn-grad bg-blue-500 slide-up">View All Contestants</Link>
            </div>
            
          </div>


        </main>

    </AppLayout>
</template>