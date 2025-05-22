<script setup>
import { onMounted, ref } from 'vue'
import replaceEmojisWithImages from '@/lib/replaceEmojis'

const props = defineProps(['emoji'])

const emoji = ref(props.emoji)
const supportsEmoji = ref(true)

onMounted(async () => {
  const emojiTest = "😀"
  supportsEmoji.value = emojiTest.length === 2
  
  // If device does not support emoji, replace emoji with images in its display
  if (!supportsEmoji.value) {
    emoji.value = await replaceEmojisWithImages(props.emoji)
  }
})
</script>

<template>
    <span v-if="!supportsEmoji" v-html="emoji" class="h-[1.15rem] inline-block mr-1 -mb-0.5"></span>
    
    <span v-else class="-ml-1">
        {{ emoji }}
    </span>
</template>