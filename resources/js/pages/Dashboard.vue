<script setup>
import { Head, Link } from '@inertiajs/vue3'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { PhUsersThree } from '@phosphor-icons/vue'

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

                <div class="dlist" v-for="comp in competitions.data" :key="comp.id">
                    <p>{{ comp.title }}</p>
                    <div class="btn">
                        <Link :href="`/dashboard/${comp.slug}`">Edit</Link>
                        <Link :href="`/dashboard/${comp.slug}/paticipants`"><PhUsersThree /></Link>
                    </div>
                </div>
                
            </div>

            <div v-if="competitions.total > 1" class="pagination mt-3">
                <p>Page {{ competitions.current_page }} of {{ competitions.total }}</p>
                <div class="page-links">
                    <Link :href="competitions.prev_page_url">Prev</Link>
                    <Link :href="competitions.next_page_url">Next</Link>
                </div>
            </div>

        </div>
    </DashboardLayout>
</template>
