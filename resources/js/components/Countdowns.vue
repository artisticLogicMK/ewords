<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import moment from 'moment-timezone'
import Countdown from '@/components/Countdown.vue'

const { competition } = defineProps(['competition'])

// Reactive current time
const now = ref(moment.tz('Africa/Lagos'))

// Update the current time every second
let timer = null
onMounted(() => {
  timer = setInterval(() => {
    now.value = moment.tz('Africa/Lagos')
  }, 1000) // update every second
})

onUnmounted(() => {
  clearInterval(timer)
})

const isFuture_reg_closes = computed(() =>
  competition.registration_closes && moment.tz(competition.registration_closes, 'Africa/Lagos').isAfter(now.value) && competition.first_voting_starts === null
)

const isFuture_first_voting_starts = computed(() =>
  competition.first_voting_starts && moment.tz(competition.first_voting_starts, 'Africa/Lagos').isAfter(now.value) && competition.first_voting_ends === null
)

const isFuture_first_voting_ends = computed(() =>
  competition.first_voting_ends && moment.tz(competition.first_voting_ends, 'Africa/Lagos').isAfter(now.value) && competition.second_voting_starts === null
)

const isFuture_second_voting_starts = computed(() =>
  competition.second_voting_starts && moment.tz(competition.second_voting_starts, 'Africa/Lagos').isAfter(now.value) && competition.second_voting_ends === null
)

const isFuture_second_voting_ends = computed(() =>
  competition.second_voting_ends && moment.tz(competition.second_voting_ends, 'Africa/Lagos').isAfter(now.value)
)
</script>


<template>
    <p v-if="isFuture_reg_closes" class="ctexts blw-semibold" v-bind="$attrs">
        <span class="opacity-84">Closes In:</span>&nbsp; <Countdown :time="competition.registration_closes" /></p>

    <p v-if="isFuture_first_voting_starts" class="ctexts blw-semibold" v-bind="$attrs">
        <span class="opacity-84">1st Stage Voting Starts In:</span>&nbsp; <Countdown :time="competition.first_voting_starts" /></p>

    <p v-if="isFuture_first_voting_ends" class="ctexts blw-semibold" v-bind="$attrs">
        <span class="opacity-84">1st Stage Voting Ends In:</span>&nbsp; <Countdown :time="competition.first_voting_ends" /></p>

    <p v-if="isFuture_second_voting_starts" class="ctexts blw-semibold" v-bind="$attrs">
        <span class="opacity-84">2nd Stage Voting Starts In:</span>&nbsp; <Countdown :time="competition.second_voting_starts" /></p>

    <p v-if="isFuture_second_voting_ends" class="ctexts blw-semibold" v-bind="$attrs">
        <span class="opacity-84">2nd Stage Voting Ends In:</span>&nbsp; <Countdown :time="competition.second_voting_ends" /></p>
</template>


<style scoped>
@reference "@/css/app.css";

.ctexts { @apply text-base }
</style>