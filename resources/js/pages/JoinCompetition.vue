<script setup>
import { Link, useForm, Head } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import PagesHeader from '@/components/PagesHeader.vue'
import Countdowns from '@/components/Countdowns.vue'
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Checkbox } from '@/components/ui/checkbox'
import InputError from '@/components/InputError.vue';
import nigeriaStates from '@/lib/nigeria-states'
import urlSlug from '@/lib/urlSlug'

const { competition } = defineProps(['competition'])

const form = useForm({
    name: '',
    slug: '',
    //age: null,
    //location: '',
    email: '',
    phone: '',
    insta: '',
    //occupation: '',
    title_of_piece: '',
    writing_experience: '',
    discovery_story: '',
    video_path: '',
    picture_path: ''
})

watch(() => form.name, (newVal) => {
    form.slug = `${urlSlug(newVal)}_${Date.now()}`
})

const termsChecked = ref(false)
const progress = ref(null)

// file input handler
function handleFileChange(e, field) {
    const file = e.target.files[0]

    // Ensure selected video is not more than 3 mins
    if (field === 'video_path') {
        // Create a video element
        const video = document.createElement('video')
        video.preload = 'metadata'

        video.onloadedmetadata = () => {
            window.URL.revokeObjectURL(video.src)
            const duration = video.duration

            if (duration > 180) {
                alert('Video must not be longer than 3 minutes.')
                e.target.value = null // reset file input
                form.video_path = null
            } else {
                form.video_path = file
            }
        }

        video.onerror = () => {
            alert('Invalid video file.')
            e.target.value = null
            form.video_path = null
        }

        video.src = URL.createObjectURL(file)
    } else {
        form[field] = file
    }
}


function submit() {
    if (!termsChecked) return 

    form.post(route('site.storeContestant', competition.slug), {
        method: 'post',
        preserveScroll: false,
        forceFormData: true,
        onProgress: (event) => {
            if (event && event.percentage) {
                progress.value = event.percentage
            }
        },
        onFinish: () => {
            progress.value = null
        },
    })
}

const breadcumb = [
    { name: "Home", url: "/" },
    { name: "Competitions", url: "/competitions" },
    { name: competition.title, url: `/competitions/${competition.slug}` },
    { name: `Join`, url: `/competitions/${competition.slug}/join` }
]
</script>

<template>

    <Head :title="`Join ${competition.title}`" />

    <AppLayout>

        <PagesHeader
            :title="`Join ${competition.title}`"
            :breadcumb="breadcumb"
            class="mb-20 scale-in"
            :image="competition.cover"
        />


        <main class="w-full max-w-2xl mx-auto mb-30 px-4 sm:px-6">

            <div class="mb-5">
                <h1 class="text-2xl sm:text-3xl text-[var(--echo-dark-400)] barlow-condensed-bold mb-1">Ready to Make Your Voice Heard?</h1>
                <p class="text-sm sm:text-base text-neutral-600/80">Your words matter—now let them be heard. Fill out the form below and take the first step toward recognition, rewards, and real impact.</p>
            </div>


            <Countdowns :competition="competition" class="countdown" />


            <form @submit.prevent="submit">
                <InputError :message="Object.keys(form.errors).length ? 'You have errors in your form!' : false" class="text-center mb-5" />

                <div class="mb-5">
                    <Label :required="true">Full Name</Label>
                    <Input type="text" v-model="form.name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="mb-5 hidden">
                    <Label>Age</Label>
                    <Input type="number" v-model="form.age" />
                    <InputError :message="form.errors.age" />
                </div>

                <div class="mb-5 hidden">
                    <Label :required="true">Location</Label>
                    <select type="text" v-model="form.location" class="input">
                        <option value="">Select</option>
                        <option v-for="state in nigeriaStates" :key="state" :value="state">{{ state }}</option>
                    </select>
                    <InputError :message="form.errors.location" />
                </div>

                <div class="mb-5">
                    <Label :required="true">
                        <template #description>Ensure your email is correct and active to avoid disqualification.</template>
                        Email
                    </Label>
                    <Input type="email" v-model="form.email" placeholder="mail@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="mb-5">
                    <Label :required="true">
                        <template #description>Ensure this number is available on WhatsApp so we can message you.</template>
                        Telephone No</Label>
                    <Input type="tel" v-model="form.phone" placeholder="+23466090989" />
                    <InputError :message="form.errors.phone" />
                </div>

                <div class="mb-5">
                    <Label>Instagram Handle</Label>
                    <Input type="text" v-model="form.insta" placeholder="@username" />
                    <InputError :message="form.errors.insta" />
                </div>

                <div class="mb-5 hidden">
                    <Label :required="true">Occupation</Label>
                    <Input type="text" v-model="form.occupation" />
                    <InputError :message="form.errors.occupation" />
                </div>

                <div class="mb-5">
                    <Label :required="true">Title of Your Piece</Label>
                    <Input type="text" v-model="form.title_of_piece" />
                    <InputError :message="form.errors.title_of_piece" />
                </div>

                <div class="mb-5">
                    <Label :required="true">How long have you been writing?</Label>
                    <textarea v-model="form.writing_experience" class="input" rows="3" style="height:auto"></textarea>
                    <InputError :message="form.errors.writing_experience" />
                </div>

                <div class="mb-5">
                    <Label :required="true">How did you discover your writing talent?</Label>
                    <textarea v-model="form.discovery_story" class="input" rows="3" style="height:auto"></textarea>
                    <InputError :message="form.errors.discovery_story" />
                </div>

                <div class="mb-5">
                    <Label :required="true">
                        <template #description>Max file size of 50MB</template>
                        Upload Spoken Word Video (Max 3 min, mp4/mov)
                    </Label>
                    <Input type="file" @change="e => handleFileChange(e, 'video_path')" accept=".mp4, .mov" />
                        <InputError :message="form.errors.video_path" />
                        <p class="text-sm text-neutral-600 mt-2">
                            Is your video file size above 50MB? Compress 
                            <a href="https://clideo.com/compress-video" class="text-blue-600 underline underline-offset-4" target="_blank">here</a>.
                        </p>
                </div>

                <div class="mb-5">
                    <Label :required="true">
                        <template #description>Max file size of 3MB</template>
                        Upload Picture (jpg/png)
                    </Label>
                    <Input type="file" @change="e => handleFileChange(e, 'picture_path')" accept=".jpg, .jpeg, .png" />
                        <InputError :message="form.errors.picture_path" />
                </div>

                <div class="flex space-x-2 mb-5">
                    <Checkbox v-model="termsChecked" class="translate-y-1" />
                    <p @click="termsChecked = !termsChecked" class="text-sm text-neutral-700 cursor-pointer">
                        I agree to the <Link href="/ter" class="text-blue-600">Terms & Conditions</Link> and give {{ $page.props.name }} permission to use my submitted materials for contest and promotional purposes.
                    </p>
                </div>


                <div v-if="progress" class="mb-5">
                    <div class="h-2 bg-gray-200 rounded overflow-hidden mb-0.5">
                        <div
                        class="bg-blue-500 h-2 transition-all duration-300"
                        :style="{ width: `${progress}%` }"
                        ></div>
                    </div>
                    <p class="text-sm text-gray-600 text-center mt-1">Uploading Contents &nbsp;-&nbsp; {{ progress }}%</p>
                </div>


                <div class="text-center">
                    <Button class="btns btn-grad bg-blue-500" :disabled="form.processing || !termsChecked" :loading="form.processing">Submit</Button>
                </div>
            </form>

        </main>

    </AppLayout>
</template>