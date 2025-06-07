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
  const now = moment.tz('Africa/Lagos')
  const duration = moment.duration(targetDate.diff(now))

  // If the countdown has ended or passed, show all zeros and stop the timer
  if (duration.asSeconds() <= 0) {
    countdown.value = '0 Minutes : 00 Seconds'
    clearInterval(timer)
    return
  }

  // Calculate remaining days, hours, minutes, and seconds
  const days = Math.floor(duration.asDays())
  const hours = duration.hours()
  const minutes = duration.minutes()
  const seconds = duration.seconds()

  // Build the countdown string dynamically based on the values
  let formattedCountdown = ''

  if (days > 0) {
    formattedCountdown += `${String(days).padStart(2, '0')} Days : `
  }

  if (hours > 0 || days > 0) {  // Only show hours if there are days or if hours is non-zero
    formattedCountdown += `${String(hours).padStart(2, '0')} Hours : `
  }

  formattedCountdown += `${String(minutes).padStart(2, '0')} Minutes : `
  formattedCountdown += `${String(seconds).padStart(2, '0')} Seconds`

  countdown.value = formattedCountdown
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