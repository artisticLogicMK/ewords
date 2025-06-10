<script setup>
import { Head } from '@inertiajs/vue3'

defineProps(['title'])
</script>

<template>
    <Head :title="title ? title : `${$page.props.name}`" />
</template>