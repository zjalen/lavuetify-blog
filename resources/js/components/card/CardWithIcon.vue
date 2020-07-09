<template>
  <v-card
    v-if="item.route"
    dark
    :color="item.color"
    @click="onClick"
  >
    <div class="d-flex flex-no-wrap justify-space-between">
      <div>
        <v-card-title
          class="display-1"
          v-text="`${item.title}`"
        ></v-card-title>

        <v-card-subtitle class="headline" v-text="item.subtitle"></v-card-subtitle>
      </div>

      <v-avatar
        class="ma-3"
        size="100"
        tile
      >
        <v-icon class="display-4">mdi-{{ item.icon }}</v-icon>
      </v-avatar>
    </div>
  </v-card>
  <v-card
    v-else
    dark
    :color="item.color"
  >
    <div class="d-flex flex-no-wrap justify-space-between">
      <div>
        <v-card-title
          class="display-1"
          v-text="`${item.title}`"
        ></v-card-title>

        <v-card-subtitle class="headline" v-text="item.subtitle"></v-card-subtitle>
      </div>

      <v-avatar
        class="ma-3"
        size="100"
        tile
      >
        <v-icon class="display-3">mdi-{{ item.icon }}</v-icon>
      </v-avatar>
    </div>
  </v-card>
</template>

<script>
export default {
  name: 'CardWithIcon',
  props: {
    item: {
      type: Object,
      default() {
        return {
          color: 'primary',
          title: 'title',
          subtitle: '10000',
          icon: 'home',
        }
      }
    },
  },
  data () {
    return {}
  },
  methods: {
    onClick () {
      if (this.item.route) {
        this.$router.push({
          path: this.item.route,
        })
      }
    },
  },
}
</script>

<style scoped>

</style>
