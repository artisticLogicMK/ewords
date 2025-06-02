<script setup>
import { Head, Link } from '@inertiajs/vue3'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { PhUsersThree, PhCheck } from '@phosphor-icons/vue'

defineProps(['competitions'])
</script>

<template>
    <Head title="Dashboard" />

    <DashboardLayout>
        <div class="px-4 sm:px-6 py-5">

            <div class="dbox">
                <div class="flex justify-between px-4 sm:px-6 py-2 border-b bdr">
                    <h1 class="text-lg text-[var(--echo-dark-400)] font-semibold">Competitions</h1>
                    <Link href="/dashboard/new" class="bg-blue-500 text-white text-sm rounded-md px-3 py-1">Add</Link>
                </div>

                <div class="dlist" v-for="(comp, i) in competitions.data" :key="comp.id">
                    <p class="flex items-center">
                        <span v-if="i === 0"><PhCheck class="text-green-500 text-xl mr-2" weight="bold" /></span>
                        {{ comp.title }}
                    </p>
                    <div class="btn">
                        <Link :href="`/dashboard/${comp.slug}`">Edit</Link>
                        <Link :href="`/dashboard/${comp.slug}/paticipants`"><PhUsersThree /></Link>
                    </div>
                </div>
                
            </div>

            <div class="pagination mt-3">
                <p>Page {{ competitions.current_page }} of {{ competitions.last_page }}</p>
                <div class="page-links">
                    <Link :href="competitions.prev_page_url" :class="{'pointer-events-none opacity-50': !competitions.prev_page_url}">Prev</Link>
                    <Link :href="competitions.next_page_url" :class="{'pointer-events-none opacity-50': !competitions.next_page_url}">Next</Link>
                </div>
            </div>

        </div>
    </DashboardLayout>
</template>
