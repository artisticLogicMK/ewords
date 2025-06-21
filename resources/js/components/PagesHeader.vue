<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Header from '@/components/Header.vue'
import { PhCaretRight } from '@phosphor-icons/vue'

const page = usePage()

const props = defineProps(['title', 'breadcumb', 'extra', 'image', 'picture'])

const bgImage = props.image ? `/storage/${props.image}` : '/assets/mesh.png'

const isItActive = link => page.url.split('?')[0] === link.url
</script>

<template>
    <header class="relative w-full overflow-hidden">

      <div class="absolute top-0 left-0 w-full h-full bg-sky-800 elliptical-background bg-cover bg-center" :style="`background-image: url(${bgImage})`">
        <div v-if="props.image" class="absolute top-0 left-0 w-full h-full bg-black/60 backdrop-blur-sm"></div>
      </div>
     

      <div class="relative">
        <Header />

        <div class="w-fit mx-auto pt-3 pb-13 text-center px-4 sm:px-6">
            <h1 class="text-white text-3xl md:text-4xl barlow-condensed-bold">{{ title }}</h1>

            <div v-if="breadcumb && breadcumb.length" class="text-white text-sm sm:text-base inline-flex flex-wrap items-center justify-center space-y-1 mt-5">
                <template v-for="(link, i) in breadcumb" :key="link.name">
                    <Link
                        :href="link.url"
                        :class="{'text-blue-400 font-semibold': isItActive(link)}"
                    >
                        {{ link.name }}
                    </Link>
                    <PhCaretRight v-if="breadcumb.length !== i + 1" weight="bold" class="mx-3" />
                </template>
            </div>

            <div v-if="extra" class="text-white text-xl barlow-condensed-semibold mt-5">
                {{ extra }}
            </div>

            <img v-if="picture" :src="picture" class="w-40 mx-auto object-center object-cover shadow-lg rounded-md mt-5" />
        </div>
     </div>

    </header>
</template>

<style>
.elliptical-background {
  animation: backgroundMotion 50s linear infinite;
}

@keyframes backgroundMotion {
  0% {
    background-position: 50% 30%;
    transform: scale(1.02)
  }
  25% {
    background-position: 70% 50%;
    transform: scale(1.2)
  }
  50% {
    background-position: 50% 70%;
    transform: scale(1.0)
  }
  75% {
    background-position: 30% 20%;
    transform: scale(1.05)
  }
  100% {
    background-position: 50% 30%;
    transform: scale(1.03)
  }
}
</style>