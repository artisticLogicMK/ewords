<script setup>
defineProps(['name', 'picture'])
</script>

<template>
    <div class="contcards flex flex-col justify-between w-full max-w-sm mx-auto items-center border bdr rounded-lg mb-5 sm:mb-0 last:mb-0 p-3 py-6 shadow-sm">
    <h1 class="text-base sm:text-lg font-bold text-[var(--echo-dark-400)] text-center mb-3">{{ name }}</h1>
      
      <div class="w-fit h-fit relative">
        <img
          :src="picture ? `/storage/${picture}` : '/assets/default_contestant.png'"
          class="w-60 h-60 object-cover object-center rounded-md" />
      </div>
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