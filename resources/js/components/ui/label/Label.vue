<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Label, type LabelProps } from 'reka-ui'
import { computed, type HTMLAttributes } from 'vue'

const props = defineProps<LabelProps & {
  class?: HTMLAttributes['class']
  required?: Boolean['required']
}>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})
</script>

<template>
  <Label
    data-slot="label"
    v-bind="delegatedProps"
    :class="
      cn(
        ' text-neutral-800 gap-2 text-sm leading-none font-semibold select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50',
        props.class,
      )
    "
  >
    <div class="mb-2.5">
      <p>
        <slot />
        <span v-if="required" class="text-red-500 ml-1">*</span>
      </p>
      <p v-if="$slots.description" class="text-xs font-normal text-neutral-600 mt-1.5">
        <slot name="description" />
      </p>
    </div>
  </Label>
</template>
