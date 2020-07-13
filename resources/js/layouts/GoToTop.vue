<template>
  <v-scale-transition>
    <v-btn
      v-if="goTopShow"
      ref="button"
      title="返回顶部"
      fab
      absolute
      fixed
      right
      bottom
      origin="center center"
      color="secondary lighten-1"
      class="mb-12 mr-3"
      style="z-index: 999"
      @click="$vuetify.goTo(target, options)"
    >
      <v-icon>mdi-chevron-up</v-icon>
    </v-btn>
  </v-scale-transition>
</template>
<script>
import * as easingPatterns from 'vuetify/es5/services/goto/easing-patterns'
export default {
  name: 'GoToTop',
  data () {
    return {
      type: 'number',
      number: 0,
      selector: '#first',
      selections: ['#first', '#second', '#third'],
      selected: 'Button',
      elements: ['Button', 'Radio group'],
      duration: 300,
      offset: 0,
      easing: 'easeInOutCubic',
      easingPatterns: Object.keys(easingPatterns),
      goTopShow: false
    }
  },
  computed: {
    target () {
      const value = this[this.type]
      if (!isNaN(value)) { return Number(value) } else { return value }
    },
    options () {
      return {
        duration: this.duration,
        offset: this.offset,
        easing: this.easing
      }
    }
  },
  mounted () {
    window.addEventListener('scroll', this.handleScroll)
  },
  destroyed () {
    window.removeEventListener('scroll', this.handleScroll)
  },
  methods: {
    handleScroll () {
      this.scrollTop =
        window.pageYOffset ||
        document.documentElement.scrollTop ||
        document.body.scrollTop
      this.goTopShow = this.scrollTop > 500
    }
  }
}
</script>
