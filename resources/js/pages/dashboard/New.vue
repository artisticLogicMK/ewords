<script setup>
import { useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button';
import moment from 'moment-timezone'

const form = useForm({
  title: '',
  slug: '',
})

form.transform((data) => ({
  ...data,
  slug: `spoken-words-${moment().format('DD-MMMM-YYYY')}-${Date.now()}`
}))

const submit = () => {
  form.post(route('competition.store'))
}
</script>


<template>
    <Head title="New Competition:Dashboard" />

    <DashboardLayout>

        <div class="px-4 sm:px-6 py-5 pb-8">
            <form @submit.prevent="submit">
                <div class="mb-5">
                    <Label :required="true">Competition Title</Label>
                    <Input v-model="form.title" type="text" placeholder="Title of competition..." required />
                </div>

                <Button :disabled="form.processing" :loading="form.processing">Continue</Button>
            </form>
        </div>

    </DashboardLayout>
</template>
