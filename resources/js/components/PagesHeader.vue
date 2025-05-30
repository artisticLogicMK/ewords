<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Header from '@/components/Header.vue'
import { PhCaretRight } from '@phosphor-icons/vue'

const page = usePage()

const props = defineProps(['title', 'breadcumb', 'extra', 'image'])

const bgImage = props.image ? props.image : '/assets/mesh.png'
</script>

<template>
    <header class="bg-sky-800 elliptical-background jbg-[url('/assets/mesh.png')] bg-cover bg-center" :style="`background-image: url(${bgImage})`">

        <Header />

        <div class="w-fit mx-auto pt-3 pb-13 text-center px-4 sm:px-6">
            <h1 class="text-white text-4xl barlow-condensed-bold">{{ title }}</h1>

            <div v-if="breadcumb && breadcumb.length" class="text-white text-base inline-flex items-center mt-5">
                <template v-for="(link, i) in breadcumb" :key="link.name">
                    <Link
                        :href="link.url"
                        class="flex"
                        :class="{'text-blue-400': page.url === link.url}"
                    >
                        {{ link.name }}
                    </Link>
                    <PhCaretRight v-if="breadcumb.length !== i + 1" weight="bold" class="mx-3" />
                </template>
            </div>

            <div v-if="extra" class="text-white text-xl barlow-condensed-semibold mt-5">
                {{ extra }}
            </div>
        </div>

    </header>
</template>

<style>
.elliptical-background {
  animation: backgroundMotion 20s linear infinite;
}

@keyframes backgroundMotion {
  0% {
    background-position: 50% 30%;
    background-size: 150% 150%;
  }
  25% {
    background-position: 70% 50%;
    background-size: 200% 140%;
  }
  50% {
    background-position: 50% 70%;
    background-size: 120% 220%;
  }
  75% {
    background-position: 30% 20%;
    background-size: 170% 180%;
  }
  100% {
    background-position: 50% 30%;
    background-size: 150% 150%;
  }
}
</style>