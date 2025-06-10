<script setup>
import { Link, router, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesHeader from '@/components/PagesHeader.vue'
import ContestantsCard from '@/components/ContestantsCard.vue'
import Countdowns from '@/components/Countdowns.vue'
import { PhMagnifyingGlass } from '@phosphor-icons/vue'
import { ref } from 'vue'

const { competition, contestants, filters } = defineProps(['competition', 'contestants', 'filters'])

const search = ref(filters.search || '')

const searchContestants = () => {
    router.get(
        route('site.contestants', competition.slug),
        { search: search.value },
        { preserveState: true, replace: true }
    )
}

const refresh = () => {
  router.reload({ preserveScroll: true, preserveState: true })
}

const breadcumb = [
    { name: "Home", url: "/" },
    { name: "Competitions", url: "/competitions" },
    { name: competition.title, url: `/competitions/${competition.slug}` },
    { name: 'Contestants', url: `/competitions/${competition.slug}/contestants` }
]
</script>


<template>

    <Head :title="`Contestants: ${competition.title}`" />

    <AppLayout>

        <PagesHeader
            :title="`Contestants: ${competition.title}`"
            :breadcumb="breadcumb"
            class="mb-20 scale-in"
            :image="competition.cover"
        />

        <main class="w-full max-w-2xl mx-auto mb-30 px-4 sm:px-6">
          
          <Countdowns :competition="competition" class="countdown" />


          <div>
            <div class="flex items-end justify-between mb-3">
              <h1 class="text-xl sm:text-2xl text-[var(--echo-dark-400)] barlow-condensed-bold">Contestants</h1>
              <div class="space-x-3">
                <button @click="refresh" class="btns-sm btn-grad">Refresh</button>
                <Link v-if="competition.registration_active" :href="`/competitions/${competition.slug}/join`" class="btns-sm btn-grad">Join</Link>
              </div>
            </div>


            <form @submit.prevent="searchContestants" class="w-full flex items-center border bdr rounded-full px-3 py-2 mb-5">
                <div class="grow mr-2">
                    <input type="search" v-model="search" class="w-full text-sm text-neutral-500 border-neutral-300" placeholder="Search contestant name or title of piece..">
                </div>
                <PhMagnifyingGlass @click="searchContestants" class="shrink-0 text-xl cursor-pointer" />
            </form>
      
            
            <div class="sm:grid grid-cols-2 gap-4 mb-5">
              <ContestantsCard
                v-for="c in contestants.data"
                :key="c.name"
                :contestant="c"
                :isVoting="competition.voting_active === 1"
                :competitionSlug="competition.slug" />
            </div>
            

            <div class="pagination">
                <p>Page {{ contestants.current_page }} of {{ contestants.last_page }}</p>
                <div class="page-links">
                    <Link :href="contestants.prev_page_url" :class="{'pointer-events-none opacity-50': !contestants.prev_page_url}">Prev</Link>
                    <Link :href="contestants.next_page_url" :class="{'pointer-events-none opacity-50': !contestants.next_page_url}">Next</Link>
                </div>
            </div>
            
            
          </div>

        </main>

    </AppLayout>
</template>