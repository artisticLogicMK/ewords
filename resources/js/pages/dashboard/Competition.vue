<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { PhFloppyDisk, PhLink } from '@phosphor-icons/vue'
import Editor from '@/components/Editor.vue'
import urlSlug from '@/lib/urlSlug'
import { watch } from 'vue'

const { competition } = defineProps(['competition'])

const form = useForm({
  title: competition.title,
  slug: competition.slug,
  content: competition.content,
  cover: null,
  //stage: competition.stage,
  past_winners_content: competition.past_winners_content,
  winner: competition.winner,
  winner_pic: null,
  first_runner: competition.first_runner,
  first_runner_pic: null,
  second_runner: competition.second_runner,
  second_runner_pic: null,
  registration_closes: competition.registration_closes,
  first_voting_starts: competition.first_voting_starts,
  first_voting_ends: competition.first_voting_ends,
  second_voting_starts: competition.second_voting_starts,
  second_voting_ends: competition.second_voting_ends
})

// file input handler
function handleFileChange(e, field) {
  form[field] = e.target.files[0]
}

watch(() => form.title, (newTitle) => {
    form.slug = urlSlug(newTitle)
})

function submit() {
  form.post(route('competition.update', competition.id), {
    method: 'put',
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
        router.replace(route('competition.show', form.slug));
    },
  })
}
</script>

<template>
    <Head :title="`${competition.title}:Dashboard`" />
{{form}}
    <DashboardLayout>
        <div class="flex justify-end px-4 sm:px-6 py-2 border-b bdr">
            <button class="flex items-center justify-center text-lg py-1 px-1.5 cursor-pointer rounded-full bg-neutral-200 mr-3">
                <PhLink />
            </button>
            <Link :href="`/dashboard/${competition.slug}/paticipants`" class="text-blue-600 text-sm border border-blue-600 px-3 py-1 rounded-md hover:text-white hover:bg-blue-600">View Competition Participants.</Link>
        </div>


        <div class="relative px-4 sm:px-6 py-5 pb-8">

            <p class="mb-5 note">
                <span v-if="competition.stage === 'end'">This competition has ended</span>
                <span v-else>This competition is in <strong>{{ competition.stage }} Stage.</strong> <a href="#second" class="text-blue-600">Set Second Stage.</a> <a href="#end" class="text-orange-600">End Competition.</a></span>
            </p>
            
            <form @submit.prevent="submit">
                <div class="mb-3">
                    <Label :required="true">Competition Title</Label>
                    <Input type="text" v-model="form.title" placeholder="Title of competition..."  />
                </div>

                <div class="mb-5">
                    <Label :required="true">Competition Cover Picture</Label>
                    <Input type="file" @change="e => handleFileChange(e, 'cover')" />
                </div>

                <div class="dbox px-3 sm:px-4 py-4 mb-5">
                    <p class="mb-5 note">This section is only used to display real-time countdowns of the competition.</p>

                    <div class="mb-3">
                        <Label>Registration Closes At</Label>
                        <Input type="datetime-local" v-model="form.registration_closes"  />
                    </div>

                    <div class="mb-3">
                        <Label>Voting Starts At</Label>
                        <Input type="datetime-local" v-model="form.first_voting_starts" />
                    </div>

                    <div class="mb-3">
                        <Label>First Stage Voting Ends At</Label>
                        <Input type="datetime-local" v-model="form.first_voting_ends" />
                    </div>

                    <div class="mb-3">
                        <Label>Second Stage Voting Starts At</Label>
                        <Input type="datetime-local" v-model="form.second_voting_starts" />
                    </div>

                    <div>
                        <Label>Second Stage Voting Ends At</Label>
                        <Input type="datetime-local" v-model="form.second_voting_ends" />
                    </div>
                </div>


                <div class="mb-5">
                    <Label>Competition Content</Label>
                    <Editor :content="form.content" v-model="form.content" />
                </div> 


                <div class="dbox px-3 sm:px-4 py-4 mb-5">
                    <h1 class="text-neutral-600 text-lg font-semibold mb-3">Set Winners</h1>
                    <div class="mb-5">
                        <Label>Winner</Label>
                        <Input type="text" v-model="form.winner" placeholder="Name" class="mb-1" />
                        <Input type="file" @change="e => handleFileChange(e, 'winner_pic')" placeholder="30" />
                    </div>

                    <div class="mb-5">
                        <Label>1st Runner Up</Label>
                        <Input type="text" v-model="form.first_runner" placeholder="Name" class="mb-1" />
                        <Input type="file" @change="e => handleFileChange(e, 'first_runner_pic')" placeholder="30" />
                    </div>

                    <div>
                        <Label>2nd Runner Up</Label>
                        <Input type="text" v-model="form.second_runner" placeholder="Name" class="mb-1" />
                        <Input type="file" @change="e => handleFileChange(e, 'second_runner_pic')" placeholder="30" />
                    </div>
                </div>


                <div class="mb-5">
                    <Label>Content to show in 'Past Winners' Page</Label>
                    <Editor :content="form.past_winners_content" v-model="form.past_winners_content" />
                </div>


                <button class="fixed bottom-3 right-3 w-12 h-12 flex items-center justify-center bg-blue-500 text-white text-2xl rounded-full shadow-md">
                    <PhFloppyDisk weight="fill" />
                </button>
                
            </form>

            <div id="second" class="dbox px-3 sm:px-4 py-4 mb-5">
                <form>
                    <h1 class="text-neutral-600 text-xl font-semibold mb-3">Determine Second Stage</h1>
                    <div class="mb-3">
                        <Label>Number of Participant to Carry</Label>
                        <Input type="number" placeholder="30" required />
                    </div>
                    <p class="mb-3 note">Top 30 of the participants will be taken ordered by number of votes and time registered, and a new vote will commence afresh. The rest of the participants will be deleted.</p>
                    <button class="btns btn-grad" @click.prevent>Start Second Stage!</button>
                </form>
            </div>

            <button id="end" class="btns btns-sm bg-orange-600 mt-8">! End This Competition</button>

        </div>
    </DashboardLayout>
</template>
