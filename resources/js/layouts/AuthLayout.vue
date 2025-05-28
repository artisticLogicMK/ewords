<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <img src="/assets/ew-logo.png" class="w-24">
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium text-[var(--echo-dark-400)] barlow-condensed-bold">{{ title }}</h1>
                        <p class="text-center text-sm text-neutral-500">{{ description }}</p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
