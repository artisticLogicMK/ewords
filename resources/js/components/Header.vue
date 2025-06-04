<script setup>
import { nextTick, onBeforeUnmount, ref, watch } from 'vue'
import { vanishIn, vanishOut } from 'animate4vue'
import { Link, usePage } from '@inertiajs/vue3'
import { PhList, PhXCircle } from '@phosphor-icons/vue'

const page = usePage()
const showNav = ref(false)

const headlinks = [
    { name: "Home", url: "/" },
    { name: "About", url: "/#about" },
    { name: "Competitions", url: "/competitions" },
    { name: "Terms and Conditions", url: "/terms" }
]

// Hide scroll bar of document if nav is open
watch(() => showNav.value, (newVal) => {
    if (newVal === true) {
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = 'auto'
    }
})

onBeforeUnmount(() => document.body.style.overflow = 'auto')
</script>

<template>
    <nav class="flex w-full max-w-6xl mx-auto items-center justify-between px-4 py-6">
        <img src="/assets/ew-logo.png" class="w-28" alt="EchoWords Logo">

        <div class="hidden md:block links">
            <Link v-for="link in headlinks" :key="link.url" :href="link.url">
                {{ link.name }}
            </Link>
        </div>

        <button @click="showNav = true" class="md:hidden text-4xl text-white cursor-pointer">
            <PhList />
        </button>

        <Teleport to="body">
            <Transition @enter="vanishIn" @leave="vanishOut" data-av-ease="bounce">
                <div v-if="showNav" class="navmodal fixed md:hidden z-[9999] top-0 left-0 flex flex-col items-center justify-center w-full h-full bg-[var(--echo-dark-400)]/[.98] px-4 sm:px-6 py-5">

                    <div class="absolute top-0 left-0 flex justify-center w-full text-4xl text-white mt-5">
                        <PhXCircle @click="showNav = false" weight="light" class="cursor-pointer hover:scale-115" />
                    </div>

                    <Link
                        v-for="link in headlinks"
                        :key="link.url" :href="link.url"
                        class="barlow-condensed-regular"
                        :class="{'active-navlink': page.url === link.url}"
                    >
                        {{ link.name }}
                    </Link>

                </div>
            </Transition>
        </Teleport>
        
    </nav>
</template>

<style scoped>
@reference "@/css/app.css";
.links a {
    @apply text-white ml-5 hover:underline underline-offset-4
}

.navmodal a {
    @apply block text-2xl text-white mb-5 hover:underline underline-offset-4
}

.navmodal a.active-navlink {
    @apply text-blue-400 font-bold
}
</style>