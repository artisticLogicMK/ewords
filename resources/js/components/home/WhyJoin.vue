<script setup>
import EmojiSupport from '@/components/EmojiSupport.vue'
</script>

<template>
    <section class="why-enter max-w-4xl mx-auto md:flex items-stretch justify-center px-4 sm:px-6">
        <div class="why-cards">
            <div class="imgcon">
                <img src="/assets/why.png" class="slide-up">
            </div>

            <div class="content">
                <p class="title barlow-condensed-regular text-in">Why Join {{ $page.props.name }}?</p>

                <div class="lists">
                    <h1 class="barlow-condensed-bold scale-in">
                        <EmojiSupport emoji="🏆" /> Win Big!
                    </h1>
                    <p class="text-in">₦500,000 grand prize, a custom plaque, premium writing tools, and bragging rights that echo beyond the stage.</p>
                </div>

                <div class="lists">
                    <h1 class=" barlow-condensed-bold scale-in">
                        <EmojiSupport emoji="🌍" /> Nationwide Exposure
                    </h1>
                    <p class="text-in">Be seen and celebrated on our social media channels, website, and across a growing community of poetry lovers.</p>
                </div>

                <div class="lists">
                    <h1 class="barlow-condensed-bold scale-in">
                       <EmojiSupport emoji="📈" /> Creative Growth
                    </h1>
                    <p class="text-in">Sharpen your skills, expand your portfolio, and gain recognition as a rising spoken word artist in Nigeria.</p>
                </div>
            </div>
        </div>


        <div class="why-cards">
            <div class="imgcon">
                <img src="/assets/how.png" class="slide-up">
            </div>

            <div class="content">
                <p class="title barlow-condensed-regular text-in">How To Enter</p>

                <div class="lists">
                    <h1 class="barlow-condensed-bold scale-in">
                        <EmojiSupport emoji="✍️" /> Prepare Your Piece
                    </h1>
                    <p class="text-in">Craft a powerful spoken word or written piece that reflects your truth, creativity, and leaves a lasting impression.</p>
                </div>

                <div class="lists">
                    <h1 class="barlow-condensed-bold scale-in">
                        <EmojiSupport emoji="📋"  /> Register and Submit
                    </h1>
                    <p class="text-in">Fill out a quick form, upload your entry, and you’re in! Submitting is fast, free, and fully online.</p>
                </div>

                <div class="lists">
                    <h1 class="barlow-condensed-bold scale-in">
                        <EmojiSupport emoji="🌟" /> Compete & Impress
                    </h1>
                    <p class="text-in">Your work goes live for public voting. Rally support, gather votes, and let your words rise to the top.</p>
                </div>
            </div>
        </div>
    </section>
</template>



<style scoped>
@reference "@/css/app.css";
.why-cards {
    @apply flex-1 h-full sm:w-4/5 md:w-auto mx-auto md:first:mr-7 rounded-xl bg-blue-100/65 overflow-hidden first:mb-10 md:first:mb-0
}
.why-cards .imgcon {
    @apply w-full relative bg-radial from-sky-600 to-[#000508] flex items-end justify-center aspect-video text-center overflow-hidden mb-3
}
.why-cards .imgcon img {
    @apply h-[95%] inline-block
}
.why-cards .content {
    @apply p-6
}
.why-cards .title {
    @apply text-xl text-black/90 mb-5
}
.why-cards .lists {
    @apply mb-6
}
.why-cards .lists h1 {
    @apply text-[var(--echo-dark-400)] text-2xl mb-2
}
.why-cards .lists p {
    @apply text-black/75 text-sm text-base
}
.why-cards {
    @apply flex-1 
}
.why-cards {
    @apply flex-1 
}
</style>