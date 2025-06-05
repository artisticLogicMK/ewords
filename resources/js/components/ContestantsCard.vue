<script setup>
import { Link } from '@inertiajs/vue3'

defineProps(['contestant', 'isVoting', 'competitionSlug'])
</script>

<template>
    <div class="contcards flex flex-col justify-between w-full max-w-xs mx-auto items-center border bdr rounded-lg mb-5 sm:mb-0 last:mb-0 p-3 py-5 shadow-sm">
      
      <div class="w-fit h-fit relative">
        <img :src="`/storage/${contestant.picture_path}`" class="w-24 h-24 object-cover object-center border-2 border-blue-500 rounded-full mb-3" />
        <div v-if="contestant.votes > 0" class="absolute top-0 right-0.5 flex justify-center items-center w-6 h-6 text-white bg-red-500 rounded-full text-xs">{{ contestant.votes }}</div>
      </div>
      
      <h1 class="text-base sm:text-lg font-bold text-[var(--echo-dark-400)] text-center">{{ contestant.name }}</h1>
      <p class="text-xs text-neutral-600/90 text-center mb-3">{{ contestant.title_of_piece }}</p>
      
      <Link
        :href="isVoting ? `/competitions/${competitionSlug}/contestant/${contestant.slug}` : ''"
        class="btns-sm btn-grad"
        :class="{'opacity-60 pointer-events-none': !isVoting}">Vote</Link>
    </div>
</template>

<style scoped>
@reference "@/css/app.css";

.contcards {
  transition: all 0.3s ease;
}

.contcards:hover {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}
</style>