<template>
    <section id="about" class="max-w-3xl mx-auto md:flex items-center justify-center px-4 sm:px-6">
        <div class="grow md:mr-8 mb-16 md:mb-0">
            <h1 class="text-[var(--echo-dark-400)] text-2xl sm:text-3xl barlow-condensed-bold text-in">About {{ $page.props.name }}</h1>
            <h1 class="text-blue-500 text-4xl sm:text-5xl barlow-condensed-bold mb-3 text-in">What We Stand For</h1>
            <p class="text-sm sm:text-base text-black/75 fade-in" data-start="top 80%">We're building a stage for Nigeria's boldest voice. {{ $page.props.name }} is a platform where writers and spoken word artists compete, connect, and get recognized. Wether you're seasoned or starting out, your voice deserves to be heard.</p>
        </div>

        <div class="shrink-0 w-54 h-54 sm:w-60 sm:h-60 mx-auto flex items-center justify-center relative">
            <div class="absolute top-0 left-0 w-full h-full bg-radial from-sky-600 to-[#000508] rounded-full scale-in"></div>
            <img src="/assets/goldhand.png" class="relative w-full rotate-in" />
        </div>
    </section>
</template>