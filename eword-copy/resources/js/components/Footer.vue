<script setup>
import { Link } from '@inertiajs/vue3'
import CallToAction from '@/components/home/CallToAction.vue'
import { PhEnvelope, PhWhatsappLogo, PhPhone } from '@phosphor-icons/vue'

// Get the current year to use in copyright
const currentYear = new Date().getFullYear()
</script>

<template>
    <CallToAction />

    <footer class="w-full bg-radial from-sky-600 to-[#080008] px-4 sm:px-6 pt-10">

        <div class="max-w-4xl mx-auto text-white/90 sm:flex items-start justify-between space-x-3 sm:space-x-5 mb-3">

            <div class="hidden sm:block self-stretch mt-13">
                <img src="/assets/ew-logo.png" class="w-28 mb-3" :alt="`${$page.props.name} Logo`">
            </div>

            <div class="foot-item mb-5 sm:mb-0">
                <h1 class="barlow-condensed-bold">Contact</h1>
                <a href="mailto:support@echowords.com" target="_blank" class="list">
                    <PhEnvelope />
                    <div>
                        <h2>Email</h2>
                        <p>support@echowords.com</p>
                    </div>
                </a>
                <a href="tel:234XXXXXXXXXX" target="_blank" class="list">
                    <PhPhone />
                    <div>
                        <h2>Phone</h2>
                        <p>+234XXXXXXXXXX</p>
                    </div>
                </a>
                <a href="https://wa.me/" target="_blank" class="list">
                    <PhWhatsappLogo />
                    <div>
                        <h2>WhatsApp</h2>
                        <p>+234XXXXXXXXXX</p>
                    </div>
                </a>
            </div>

            <div class="foot-item">
                <h1 class="barlow-condensed-bold -mb-3">Pages</h1>
                <div>
                    <Link href="/past-winners" class="links">Past Winners</Link>
                    <Link href="/" class="links">Home</Link>
                    <Link href="/competitions" class="links">Competitions</Link>
                    <Link href="/terms" class="links">Terms and Conditions</Link>
                    <Link href="/report" class="links">Report a Contestant</Link>
                </div>
            </div>

        </div>

        <div class="w-full max-w-4xl mx-auto text-white/90 text-xs sm:text-sm flex items-center justify-between border-t border-white/10 py-3">
            <p>&copy;{{currentYear}}&nbsp;{{ $page.props.name }}.</p>
            <a href="" class="text-end"><span class="text-sky-400">Developed by</span> ArtisticLogic</a>
        </div>

        <div class="w-full max-w-4xl mx-auto text-white/90 text-sm border-t border-white/10 py-3">
            <h1 class="font-semibold mb-2">Disclaimer</h1>
            <p class="text-xs sm:text-sm">Our contests constitutes a competition and NOT an investment opportunity. We strongly discourage taking extreme measures for the purpose of participation or winning our contests. Participants assume all risks.</p>
        </div>

    </footer>
</template>

<style>
@reference "@/css/app.css";

.foot-item h1 {
    @apply text-base sm:text-lg mb-3 sm:mb-5
}
.foot-item .list {
    @apply flex items-center text-xs sm:text-sm mb-3
}
.foot-item .links {
    @apply text-xs sm:text-sm inline-block sm:block hover:underline underline-offset-4 mb-0.5 sm:mb-2 mr-3 sm:mr-0
}
.foot-item .links:hover {
    text-decoration: underline;
}
.foot-item .list svg {
    @apply mr-3 text-xl sm:text-2xl
}
.foot-item .list div {
    @apply text-xs sm:text-sm
}
.foot-item .list h2 {
    @apply font-semibold
}
</style>