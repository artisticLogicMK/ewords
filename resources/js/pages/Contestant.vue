<script setup>
import { ref } from 'vue'
import { Link, router, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesHeader from '@/components/PagesHeader.vue'
import Countdowns from '@/components/Countdowns.vue'
import { PhFacebookLogo, PhWhatsappLogo, PhLinkedinLogo, PhLink, PhXLogo } from '@phosphor-icons/vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import Button from '@/components/ui/button/Button.vue'
import { Dialog, DialogContent, DialogTrigger, DialogClose } from '@/components/ui/dialog'


const { competition, contestant, shareUrl } = defineProps(['competition', 'contestant', 'shareUrl'])

const votes = ref(contestant.votes)

const copied = ref(false)
const votesSel = ref({ value: 10, price: '₦500', amount: 500 })

function copyToClipboard() {
  navigator.clipboard.writeText(shareUrl).then(() => {
    copied.value = true

    // Reset after 2 seconds
    setTimeout(() => {
      copied.value = false
    }, 2000)
  })
}

async function pay() {
  payerDetails.value.load = true

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const response = await fetch(route('contestant.vote', {
      competition: competition.slug,
      contestant: contestant.slug,
    }), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        votes: votesSel.value.value,
        amount: votesSel.value.amount * 100,
        email: payerDetails.value.email,
        phone: payerDetails.value.phone,
        prev_votes: contestant.votes,
      }),
    });

    const data = await response.json();

    if (data.redirect_url) {
      window.location.href = data.redirect_url;
    } else {
      alert('Could not initiate payment. Try again.');
      console.error(data);
    }
  } catch (error) {
    console.error('Payment error:', error);
    alert('Something went wrong while trying to pay.');
  } finally {
    payerDetails.value.load = false;
    document.querySelector("#xDialg").click();
  }
}


const voteOptions = [
    { value: 10, price: '₦500', amount: 500 },
    { value: 50, price: '₦2,500', amount: 2500 },
    { value: 100, price: '₦5,000', amount: 5000 },
    { value: 500, price: '₦25,000', amount: 25000 },
    { value: 1000, price: '₦50,000', amount: 50000 }
]

const payerDetails = ref({
    email: 'mk.artisticlogic@gmail.com',
    phone: '0801389467',
    load: false
})

const breadcumb = [
    { name: "Home", url: "/" },
    { name: "Competitions", url: "/competitions" },
    { name: competition.title, url: `/competitions/${competition.slug}` },
    { name: 'Contestants', url: `/competitions/${competition.slug}/contestants` },
    { name: `Vote for ${contestant.name}`, url: `/competitions/${competition.slug}/contestants/${contestant.slug}` }
]
</script>


<template>

    <Head :title="`Vote for ${contestant.name} | ${competition.title}`" />

    <AppLayout>

        <PagesHeader
            :title="contestant.name"
            :breadcumb="breadcumb"
            class="mb-20 scale-in"
            :image="contestant.picture_path"
            :picture="contestant.picture_path ? `/storage/${contestant.picture_path}` : '/assets/default_contestant.png'"
        />


        <main class="w-full md:w-fitj max-w-4xl mx-auto mb-30 px-4 sm:px-6">

            <div v-if="$page.props.flash.success" class="alerts success-alert mb-5">{{ $page.props.flash.success }}</div>
          
            <Countdowns :competition="competition" class="countdown" />

            <div class="md:flex items-start justify-center">
                <video
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



                    <div class="mb-7">
                        <p class="text-sm text-neutral-600 mb-2">Desired number of votes to give</p>
                        <select v-model="votesSel" class="input text-lg max-w-[250px] mb-4" style="font-size: 1.125rem;">
                            <option v-for="opt in voteOptions" :key="opt.value" :value="opt">
                                {{ opt.value }} votes – {{ opt.price }}
                            </option>
                        </select>

                        <Dialog>
                            <DialogTrigger as-child>
                                <button class="btns btn-grad">
                                    Pay {{ votesSel.price }} For {{ votesSel.value }} Votes
                                </button>
                            </DialogTrigger>
                            <DialogContent class="bg-white">
                                <h1 class="text-xl text-neutral-700 font-semibold mb-2">Enter your details to proceed</h1>

                                <form @submit.prevent="pay">
                                    <div class="mb-3">
                                        <Label required="true">Email</Label>
                                        <Input type="email" v-model="payerDetails.email" placeholder="mail.example.com" required />
                                    </div>

                                    <div class="mb-5">
                                        <Label required="true">Phone No</Label>
                                        <Input type="tel" v-model="payerDetails.phone" placeholder="+2346596058000" required />
                                    </div>

                                    <div class="flex justify-between space-x-3">
                                        <Button :disabled="payerDetails.load" :loading="payerDetails.load">
                                            Pay {{ votesSel.price }} For {{ votesSel.value }} Votes
                                        </Button>
                                        <DialogClose>
                                            <Button id="xDialg" @click.prevent variant="outline">Cancel</Button>
                                        </DialogClose>
                                    </div>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>



                    <div>
                        <h1 class="text-lg sm:text-xl text-neutral-700 font-semibold mb-3">
                            About {{ contestant.name }}'s Work
                        </h1>

                        <div class="contestant-info">
                            <h1>Piece Title</h1>
                            <p>{{ contestant.title_of_piece }}</p>
                        </div>

                        <div class="contestant-info hidden">
                            <h1>Location</h1>
                            <p>{{ contestant.location }}</p>
                        </div>

                        <div class="contestant-info hidden">
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

                        <div class="contestant-info">
                            <h1>Instagram Handle</h1>
                            <p class="whitespace-pre-line">{{ contestant.insta }}</p>
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