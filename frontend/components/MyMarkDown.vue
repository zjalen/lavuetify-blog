<template>
  <Editor
    ref="render"
    v-model="text"
    mode="Rendered"
    hint="Test"
    native-emoji
    :outline="false"
    :preview="true"
  />
</template>

<script>
import { Editor } from 'vuetify-markdown-editor'

// CSS for Editor
import 'vuetify-markdown-editor/dist/vuetify-markdown-editor.css'

/* Editor is a Vue component
 * Renderer is the internal rendering function
 * Toolbar is a Vue component used for some WYSIWYG functions
 */

export default {
  components: {
    Editor
  },
  props: {
    text: {
      default: '',
      type: String
    }
  },
  data () {
    return {}
  }
}
</script>
