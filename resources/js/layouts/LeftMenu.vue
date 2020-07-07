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
        menu_list: [
          {
            group: '菜单列表',
            current: null,
            children: [
              {
                title: '首页',
                icon: 'home',
                sign: 'home',
              },
              {
                title: '小区信息',
                icon: 'office-building',
                sign: 'communities',
              },
              {
                title: '水表信息',
                icon: 'gauge',
                sign: 'meters',
              },
              {
                title: '用水记录',
                icon: 'calendar-month-outline',
                sign: 'room-histories',
              },
              {
                title: '水表充值单',
                icon: 'cash-100',
                sign: 'meter-recharge-orders',
              },
              {
                title: '水卡充值单',
                icon: 'cash-usd-outline',
                sign: 'card-recharge-orders',
              },
            ]
          },
          {
            group: '下载中心',
            current: null,
            children: [
              {
                title: '水表充值报表',
                icon: 'file-document-box-multiple-outline',
                sign: 'meter-recharge-report',
              },
              {
                title: '水卡充值报表',
                icon: 'file-document-box-multiple-outline',
                sign: 'card-recharge-report',
              },
            ]
          },
        ],
      }
    },
    mounted () {
      this.show = this.$store.getters.drawer
      this.$VM.$on('switchDrawer', this.onSwitchDrawer)
      this.updateMenuSign()
    },
    computed: {},
    watch: {
      show () {
        this.$store.dispatch('actionSetDrawer', this.show)
      },
      '$route': function (to, from) {
        this.updateMenuSign()
      },
    },
    methods: {
      onClick (index, i, sign) {
        this.menu_list.forEach((value,key) => {
          if (key === index) {
            this.menu_list[index].current = i;
          }else {
            this.menu_list[key].current = null;
          }
        })
        if (sign !== this.$route.name) {
          this.$router.push({
            name: sign,
          })
        }
      },
      onSwitchDrawer (val) {
        this.show = val
      },
      updateMenuSign() {
        this.menu_list.forEach((value, key)=> {
          value.children.forEach((v, k)=> {
            if (v.sign === this.$route.name) {
              this.menu_list[key].current = k;
            }
          })
        })
      }
    },
  }
</script>

<style scoped>

</style>
