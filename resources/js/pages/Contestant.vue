<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesHeader from '@/components/PagesHeader.vue'
import Countdowns from '@/components/Countdowns.vue'
import { PhFacebookLogo, PhWhatsappLogo, PhLinkedinLogo, PhLink, PhXLogo } from '@phosphor-icons/vue'

const { competition, contestant, shareUrl } = defineProps(['competition', 'contestant', 'shareUrl'])

const votes = ref(contestant.votes)

const copied = ref(false)
const votesSel = ref({ value: 10, price: '₦500' })

function copyToClipboard() {
  navigator.clipboard.writeText(shareUrl).then(() => {
    copied.value = true

    // Reset after 2 seconds
    setTimeout(() => {
      copied.value = false
    }, 2000)
  })
}

function pay() {
    votes.value += votesSel.value.value
}

const voteOptions = [
    { value: 10, price: '₦500' },
    { value: 50, price: '₦2,500' },
    { value: 100, price: '₦5,000' },
    { value: 500, price: '₦25,000' },
    { value: 1000, price: '₦50,000' },
]

const breadcumb = [
    { name: "Home", url: "/" },
    { name: "Competitions", url: "/competitions" },
    { name: competition.title, url: `/competitions/${competition.slug}` },
    { name: 'Contestants', url: `/competitions/${competition.slug}/contestants` },
    { name: `Vote for ${contestant.name}`, url: `/competitions/${competition.slug}/contestants/${contestant.slug}` }
]
</script>


<template>

    <OpenGraph
        :title="`${contestant.name} | ${competition.title}`"
        :description="`Cast your vote for ${contestant.name}’s powerful piece in the ${competition.title}.`"
        :image="contestant.picture_path ? `/storage/${contestant.picture_path}` : '/assets/default_contestant.png'" />

    <AppLayout>

        <PagesHeader
            :title="contestant.name"
            :breadcumb="breadcumb"
            class="mb-20 scale-in"
            :image="contestant.picture_path"
            :picture="contestant.picture_path ? `/storage/${contestant.picture_path}` : '/assets/default_contestant.png'"
        />


        <main class="w-full md:w-fitj max-w-4xl mx-auto mb-30 px-4 sm:px-6">
          
            <Countdowns :competition="competition" class="countdown" />

            <div class="md:flex items-start justify-center">
                <video
                    v-if="contestant.video_path"
                    :src="contestant.video_path ? `/storage/${contestant.video_path}` : '/assets/default_video.webm'"
                    controls
                    id="video"
                    class="w-full max-w-sm mx-auto rounded-lg md:mr-6 mb-5 md:mb-0"
                ></video>


                <div class="grow">
                    <h1 class="text-xl sm:text-2xl text-neutral-700 font-semibold mb-5">{{ contestant.name }}</h1>
                    <h1 class="text-2xl sm:text-3xl text-blue-500 font-bold mb-5">{{ votes }} Votes(s)</h1>

                    <div class="sharelinks flex flex-wrap gap-2 sm:gap-3 mb-6">
                        <button @click="copyToClipboard" class="relative" title="Copy Link">
                            <PhLink class="mr-2" /> Copy
                            <span
                                v-if="copied"
                                class="absolute w-full bottom-full left-0 mt-1 text-sm text-green-600 text-center mb-0.5"
                            >
                                Copied!
                            </span>
                        </button>

                        <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`" class="hover:bg-[#1877F2!important]" target="_blank" rel="noopener" title="Share on Facebook">
                            <PhFacebookLogo />
                        </a>
                        <a :href="`https://twitter.com/intent/tweet?url=${encodeURIComponent(shareUrl)}`" class="hover:bg-[#000000!important]" target="_blank" rel="noopener" title="Share on X">
                            <PhXLogo />
                        </a>
                        <a :href="`https://wa.me/?text=${encodeURIComponent(shareUrl)}`" class="hover:bg-[#25D366!important]" target="_blank" rel="noopener" title="Share on WhatsApp">
                            <PhWhatsappLogo />
                        </a>
                        <a :href="`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl)}`" class="hover:bg-[#0077B5!important]" target="_blank" rel="noopener" title="Share on LinkedIn">
                            <PhLinkedinLogo />
                        </a>
                    </div>


                    <form @submit.prevent="pay" class="mb-7">
                        <p class="text-sm text-neutral-600 mb-2">Desired number of votes to give</p>
                        <select v-model="votesSel" class="input max-w-[250px] mb-4">
                            <option v-for="opt in voteOptions" :key="opt.value" :value="opt">
                                {{ opt.value }} votes – {{ opt.price }}
                            </option>
                        </select>

                        <button class="btns btn-grad">
                            Pay {{ votesSel.price }} For {{ votesSel.value }} Votes
                        </button>
                    </form>


                    <div>
                        <h1 class="text-lg sm:text-xl text-neutral-700 font-semibold mb-3">
                            About {{ contestant.name }}'s Work
                        </h1>

                        <div class="contestant-info">
                            <h1>Piece Title</h1>
                            <p>{{ contestant.title_of_piece }}</p>
                        </div>

                        <div class="contestant-info">
                            <h1>Location</h1>
                            <p>{{ contestant.location }}</p>
                        </div>

                        <div class="contestant-info">
                            <h1>Occupation</h1>
                            <p>{{ contestant.occupation }}</p>
                        </div>

                        <div class="contestant-info">
                            <h1>How long have they been writing?</h1>
                            <p>{{ contestant.writing_experience }}</p>
                        </div>

                        <div class="contestant-info">
                            <h1>How did they discovered their writing talent?</h1>
                            <p class="whitespace-pre-line">{{ contestant.discovery_story }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </main>

    </AppLayout>
</template>

<style scoped>
@reference "@/css/app.css";

.sharelinks button, .sharelinks a {
    @apply flex items-center justify-center bg-neutral-200/60 text-neutral-600 border border-neutral-400/50 rounded-full
}
.sharelinks svg {
    @apply text-lg sm:text-xl
}
.sharelinks button {
    @apply px-2 h-8 text-xs sm:text-sm
}
.sharelinks a {
    @apply h-8 w-8 hover:text-[#ffffff!important] duration-200
}

.contestant-info {
    @apply mb-3
}
.contestant-info h1 {
    @apply text-sm font-semibold text-neutral-800
}
.contestant-info {
    @apply text-sm text-neutral-600
}

@media (min-width: 500px) {
    #video {
        @apply w-full
    }
}
</style>