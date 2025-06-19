<script setup>
import { Link, router } from '@inertiajs/vue3'

defineProps(['contestant', 'isVoting', 'competitionSlug'])

function deleteContestant(slug) {
    // Return confirm dialog to proceed
    if (confirm('Are you sure you want to delete this contestant?')) {
        router.delete(route('contestant.destroy', slug), {
            //preserveScroll: true,
            onSuccess: () => {
              // Optional: show a success toast or redirect
              alert('Deleted')
            },
        })
    }
}
</script>

<template>
    <div class="contcards flex flex-col justify-between w-full max-w-xs mx-auto items-center border bdr rounded-lg mb-5 sm:mb-0 last:mb-0 p-3 py-5 shadow-sm">
      
      <div class="w-fit h-fit">
        <img
          :src="contestant.picture_path ? `/storage/${contestant.picture_path}` : '/assets/default_contestant.png'"
          class="w-28 h-28 object-cover object-center border-2 border-blue-500 rounded-full mb-3" />
      </div>
      
      <h1 class="text-base sm:text-lg font-bold text-[var(--echo-dark-400)] text-center">{{ contestant.name }}</h1>
      <p class="text-xs text-neutral-600/90 text-center mb-3">{{ contestant.title_of_piece }}</p>

      <div v-if="contestant.votes > 0" class="text-red-500 text-xs font-semibold mb-1">{{ contestant.votes }} Votes</div>
      
      <Link
        :href="isVoting ? `/competitions/${competitionSlug}/contestants/${contestant.slug}` : ''"
        class="btns-sm btn-grad"
        :class="{'opacity-60 pointer-events-none': !isVoting}">Vote</Link>

        <button
          v-if="$page.props.auth.user && $page.url.includes('contestants')"
          @click="deleteContestant(contestant.slug)"
          class="font-semibold text-xs mt-2 underline underline-offset-4">Remove Contestant</button>
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