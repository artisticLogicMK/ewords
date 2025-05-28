<script setup>
import { onMounted, ref } from 'vue'
import Quill from 'quill'
import 'quill/dist/quill.snow.css'

const editorToolbar = ref(null)
const editorContainer = ref(null)
let quillInstance = null

const model = defineModel() // Binding content model
const props = defineProps(['content'])

onMounted(() => {
  const toolbarOptions = [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ color: [] }, { background: [] }],
    [{ script: 'sub' }, { script: 'super' }],
    [{ list: 'ordered' }, { list: 'bullet' }],
    [{ indent: '-1' }, { indent: '+1' }],
    [{ align: [] }],
    ['blockquote', 'code-block'],
    ['link', 'image', 'video', 'formula'],
    ['clean'],
  ]

  quillInstance = new Quill(editorContainer.value, {
    modules: {
      toolbar: {
        container: toolbarOptions,
      },
    },
    placeholder: 'Compose your content...',
    theme: 'snow',
  })

  // If there is pre-saved content, load it
  if (props.content) quillInstance.clipboard.dangerouslyPasteHTML(props.content)

  // Update the model when the editor content changes
  quillInstance.on('text-change', () => {
    model.value = quillInstance.root.innerHTML
  })
})
</script>


<template>
  <div class="quill-wrapper">
    <div ref="editorToolbar" class="editor-toolbar">
      <!-- Quill toolbar will be attached here -->
    </div>
    <div ref="editorContainer" class="editor-container"></div>
  </div>
</template>

<style scoped>
@reference "@/css/app.css";

.ql-container {
  @apply rounded-b-md min-h-36
}
.quill-wrapper .ql-toolbar.ql-snow {
  border-top-right-radius: 0.375rem!important;
  border-top-left-radius: 0.375rem!important;
}
</style>
