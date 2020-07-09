<template>
  <v-card
  >
    <div ref="myChart" :style="`height: ${item.height ? item.height : '400px'};`"></div>
  </v-card>
</template>

<script>
export default {
  name: 'CardWithChart',
  props: {
    item: {
      type: Object,
      default () {
        return {}
      },
    },
  },
  data () {
    return {
      chart_option: {},
    }
  },
  mounted () {
    if (this.item.chart_option) {
      this.chart_option = this.item.chart_option
    }
  },
  watch: {
    chart_option: {
      handler () {
        this.initChart()
      },
      deep: true,
    },
  },
  methods: {
    initChart () {
      let myChart = this.$echarts.init(this.$refs.myChart) //这里是为了获得容器所在位置
      window.addEventListener('resize', () => { myChart.resize() })
      myChart.setOption(this.chart_option)
    },
  },
}
</script>

<style scoped>

</style>
