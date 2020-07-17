<template>
  <v-navigation-drawer
    v-model="show"
    app
    :disable-resize-watcher="true"
    clipped
  >
    <v-list dense>
      <div v-for="(item, index) in menu_list"
           :key="index">
        <v-subheader
          class="mt-4 grey--text text--darken-1">
          {{item.group}}
        </v-subheader>
        <v-list-item-group v-model="item.current" color="primary">
          <v-list-item v-for="(menu, i) in item.children" :key="i" @click="onClick(index, i, menu.sign)" link>
            <v-list-item-action>
              <v-icon>mdi-{{ menu.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{menu.title}}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </div>
    </v-list>

  </v-navigation-drawer>
</template>

<script>
export default {
  name: 'LeftMenu',
  props: {},
  data () {
    return {
      show: false,
      menu_list: []
    }
  },
  mounted () {
    this.menu_list = this.$store.getters.menu_list
    this.show = this.$store.getters.drawer
    this.$VM.$on('switchDrawer', this.onSwitchDrawer)
    this.updateMenuSign()
  },
  computed: {},
  watch: {
    show () {
      this.$store.dispatch('actionSetDrawer', this.show)
    },
    '$route': function () {
      this.updateMenuSign()
    },
  },
  methods: {
    onClick (index, i, sign) {
      this.menu_list.forEach((value, key) => {
        if (key === index) {
          this.menu_list[index].current = i
        }
        else {
          this.menu_list[key].current = null
        }
      })
      if (sign !== this.$route.name) {
        this.$router.push({
          name: sign,
        })
      }
      setTimeout(() => {
        this.updateMenuSign()
      }, 10)
    },
    onSwitchDrawer (val) {
      this.show = val
    },
    updateMenuSign () {
      this.menu_list.forEach((value, key) => {
        value.children.forEach((v, k) => {
          if (v.sign === this.$route.name) {
            this.menu_list[key].current = k
          }
        })
      })
    },
  },
}
</script>

<style scoped>

</style>
