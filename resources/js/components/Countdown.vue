<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import moment from 'moment-timezone'

const { time } = defineProps(['time'])

// A reactive variable to store the countdown display
const countdown = ref('Loading...')

// Set the target date/time in Nigeria's timezone (Africa/Lagos)
const targetDate = moment.tz(time, 'Africa/Lagos')

// Variable to store the countdown timer interval
let timer = null

// Function to update the countdown every second
function updateCountdown() {
  // Get the current time in Africa/Lagos timezone
  const now = moment.tz('Africa/Lagos')

  // Calculate the time difference from now to the target date
  const duration = moment.duration(targetDate.diff(now))

  // If the countdown has ended or passed, show all zeros and stop the timer
  if (duration.asSeconds() <= 0) {
    countdown.value = '00 Days : 00 Hours : 00 Minutes : 00 Seconds'
    clearInterval(timer)
    return
  }

  // Calculate remaining days, hours, minutes, and seconds
  const days = String(Math.floor(duration.asDays())).padStart(2, '0')
  const hours = String(duration.hours()).padStart(2, '0')
  const minutes = String(duration.minutes()).padStart(2, '0')
  const seconds = String(duration.seconds()).padStart(2, '0')

  // Set the formatted countdown string
  countdown.value = `${days} Days : ${hours} Hours : ${minutes} Minutes : ${seconds} Seconds`
}

// Start the countdown when the component is mounted
onMounted(() => {
  updateCountdown() // Run immediately
  timer = setInterval(updateCountdown, 1000) // Update every second
})

// Clear the interval when the component is unmounted
onUnmounted(() => {
  clearInterval(timer)
})
</script>

<template>
  {{ countdown }}
</template>