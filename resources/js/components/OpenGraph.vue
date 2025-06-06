<script setup>
import { Head } from '@inertiajs/vue3'

defineProps(['image', 'title', 'description'])
</script>

<template>
    <Head :title="title ? title : `${$page.props.name}`">
        <meta v-if="description" name="description" :content="description" />
        <!-- Open Graph -->
        <meta v-if="image" property="og:image" :content="`${$page.props.ziggy.url}${image}`" />

        <!-- Twitter Card -->
        <meta v-if="image" name="twitter:card" content="summary_large_image" />
        <meta v-if="image" name="twitter:image" :content="`${$page.props.ziggy.url}${image}`" />
    </Head>
</template>