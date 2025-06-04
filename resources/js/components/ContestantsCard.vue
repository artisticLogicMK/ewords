<script setup>
import { Link } from '@inertiajs/vue3'

defineProps(['contestant'])
</script>

<template>
    <div class="contcards flex flex-col justify-between w-full max-w-xs mx-auto items-center border bdr rounded-lg mb-5 sm:mb-0 last:mb-0 p-3 py-5 shadow-sm">
      
      <div class="w-fit h-fit relative">
        <img src="/assets/why.png" class="w-24 h-24 object-cover object-center border-2 border-blue-500 rounded-full mb-3" />
        <div class="absolute top-0 right-0.5 flex justify-center items-center w-6 h-6 text-white bg-red-500 rounded-full text-xs">{{ contestant.votes }}</div>
      </div>
      
      <h1 class="text-base sm:text-lg font-bold text-[var(--echo-dark-400)] text-center mb-3">{{ contestant.name }}</h1>
      
      <Link class="btns-sm btn-grad">Vote</Link>
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