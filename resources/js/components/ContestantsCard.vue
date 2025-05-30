<script setup>
import { Link } from '@inertiajs/vue3'

defineProps(['contestant'])
</script>

<template>
    <div class="flex flex-col items-center border bdr rounded-lg mb-5 last:mb-0 shadow-sm">
      
      <div class="w-fit h-fit relative">
        <img :src="contestant.pic" class="w-24 object-cover object-center border-2 border-blue-500 mb-3" />
        <div class="absolute -top-2 -right-2 flex justify-center items-center w-6 h-6 text-white bg-red-500 rounded-full text-xs">{{ contestant.vote }}</div>
      </div>
      
      <h1 class="text-2xl font-bold text-[var(--echo-dark-400)] mb-3">{{ contestant.name }}</h1>
      
      <Link class="btns-small btn-grad">Vote</Link>
    </div>
</template>