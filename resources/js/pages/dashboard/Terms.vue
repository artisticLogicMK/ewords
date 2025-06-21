<script setup>
import { nextTick } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { Label } from '@/components/ui/label'
import Editor from '@/components/Editor.vue'

const { terms } = defineProps(['terms'])

const form = useForm({
  content: terms.content,
})

function submit() {
    form.post(route('terms.update'), {
        method: 'put',
        preserveScroll: true,
        onSuccess: async () => {
            await nextTick()
            if (document.getElementById("flash")) document.getElementById("flash").scrollIntoView()
        },
    })
}
</script>

<template>
    <Head title="Terms:Dashboard" />

    <DashboardLayout>
        


        <div class="relative px-4 sm:px-6 py-5 pb-8">

            <div v-if="$page.props.flash.error" id="flash" class="alerts error-alert mb-5">{{ $page.props.flash.error }}</div>

            <div v-if="$page.props.flash.success" id="flash" class="alerts success-alert mb-5">{{ $page.props.flash.success }}</div>
     
            <form @submit.prevent="submit">

                <div class="mb-5">
                    <Label>Terms & Conditions Content</Label>
                    <Editor :content="form.content" v-model="form.content" />
                </div>

                <button class="btns btns-sm btn-grad">Update</button>
            </form>

        </div>
    </DashboardLayout>
</template>
