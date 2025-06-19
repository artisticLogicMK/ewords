<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="w-full max-w-4xl mx-auto min-h-screen border border-neutral-200 border-t-transparent border-b-transparent">
        <div class="text-center py-3 border-b border-neutral-200">
            <h1 class="text-xl font-medium text-[var(--echo-dark-400)] barlow-condensed-bold px-4 px-6">{{ $page.props.name }} Control Panel</h1>
        </div>

        <div class="dtop text-center text-neutral-500 py-2 border-b border-neutral-200 text-sm px-4 px-6 py-3 space-x-3 space-y-2">
            <Link href="/dashboard" :class="{'active': $page.url === '/dashboard'}">Competitions</Link>
                <span>-</span>
            <Link href="/dashboard/new" :class="{'active': $page.url === '/dashboard/new'}">New Competition</Link>
                <span>-</span>
            <Link href="/dashboard/password" :class="{'active': $page.url === '/dashboard/password'}">Change Password</Link>
                <span>-</span>
            <Link href="/logout" method="post" class="">Log Out</Link>
        </div>

        <slot />

    </div>
</template>

<style>
@reference "@/css/app.css";

.dtop a {
    @apply hover:opacity-80
}
.dtop a.active {
    @apply text-blue-500 font-bold
}

.dbox {
    @apply border border-neutral-200 rounded-lg
}
.dbox .dlist {
    @apply w-full flex items-center justify-between text-sm px-4 py-2 hover:bg-neutral-100 border-b border-neutral-200 last:border-b-transparent
}
.dbox .dlist p {
    @apply text-[var(--echo-dark-400)]/75
}
.dbox .dlist .btn {
    @apply flex space-x-3
}
.dbox .dlist a {
    @apply block text-neutral-500 hover:text-neutral-600 font-light border border-neutral-200 hover:bg-neutral-300 rounded-md px-3 py-1
}
.dbox .dlist svg {
    @apply text-lg
}

.note {
    @apply text-neutral-500 text-sm border-l-2 border-blue-500 px-3 py-1
}

.dashboard-alert {
    @apply text-white text-base rounded-md mb-5 px-3 py-2
}
</style>