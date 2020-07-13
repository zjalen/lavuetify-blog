<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="desserts"
      :options.sync="options"
      :server-items-length="totalDesserts"
      :loading="loading"
      :footer-props="{
              showFirstLastPage: true,
              itemsPerPageOptions: [10,20,100]
            }"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-row class="primary lighten-2 mx-0">
          <v-col cols="12" class="d-flex flex-row justify-end pr-4">
            <v-btn
              v-for="(button, key) in header_actions"
              :key="key"
              tile
              outlined
              color="white"
              class="mx-1"
              @click="$emit('headerAction',{ sign: button.sign })"
            >
              {{button.text}}
              <v-icon right color="white">mdi-{{button.icon}}</v-icon>
            </v-btn>
            <v-spacer/>
            <v-btn v-if="filters.length > 0" tile outlined class="mx-1 white--text" @click="onRefresh">
              撤销
              <v-icon right color="white">mdi-refresh</v-icon>
            </v-btn>
            <v-btn v-if="filters.length > 0" tile outlined class="mx-1 white--text" @click="dialog = true">
              搜索
              <v-icon right color="white">mdi-card-search-outline</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </template>
      <template v-for="(v,k) in chip_columns" v-slot:[v]="{ item }">
        <v-chip :key="k" small color="primary" dark>{{ item[v.split('.')[1]] }}
        </v-chip>
      </template>
      <template v-for="(value,key) in bool_columns" v-slot:[value]="{ item }">
        <!--        <v-chip :key="key" small :color="getBoolColor(item[value.split('.')[1]])" dark>{{-->
        <!--          getBoolValue(item[value.split('.')[1]])-->
        <!--          }}-->
        <!--        </v-chip>-->
        <v-switch :key="key" v-model="item[value.split('.')[1]]" inset @change="onSwitch(value.split('.')[1], item)"></v-switch>
      </template>
      <template v-for="(val,key) in image_columns" v-slot:[val]="{ item }">
        <v-img :key="key" :src="item[val.split('.')[1]]" aspect-ratio="1.7" class="ma-2"></v-img>
      </template>
      <template v-slot:item.amount="{ item }">
        <v-chip small :color="getLevelColor(item.amount)" dark>{{ item.amount }}</v-chip>
      </template>
      <template v-slot:item.valve="{ item }">
        <v-chip small :color="getValveColor(item.valve)" dark>{{ item.valve ? '关' : '开' }}</v-chip>
      </template>
      <template v-slot:item.action="{ item }">
                <span v-for="(action, k) in actions"
                      :key="k">
                    <v-tooltip v-if="action.icon" bottom>
                        <template v-slot:activator="{ on }">
                            <v-icon
                              small
                              class="mx-1"
                              v-on="on"
                              @click="onEmit(action.sign, item)"
                            >
                                mdi-{{action.icon}}
                            </v-icon>
                        </template>
                        <span>{{action.tip}}</span>
                    </v-tooltip>

                    <v-btn
                      text
                      small
                      v-else
                      :color="action.color ? action.color : 'primary'"
                      :title="action.tip"
                      @click="onEmit(action.sign, item)"
                    >
                        {{action.text}}
                    </v-btn>
                </span>
      </template>
    </v-data-table>
    <v-row class="d-flex justify-center mx-0">
      <v-col :xs="12" :sm="10" :md="6" class="d-flex justify-center">
        <v-pagination v-model="options.page" :length="pageCount"></v-pagination>
      </v-col>
    </v-row>
    <v-dialog
      v-model="dialog"
      :width="$vuetify.breakpoint.mdAndUp ? '50%' : '90%'"
    >
      <v-card>
        <v-card-title class="primary white--text">
          表格检索
          <v-spacer></v-spacer>
          <v-btn icon @click="dialog=false">
            <v-icon color="white">mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-row class="mx-0">
          <v-col cols="12">
            <filter-form :filters="table_filters" @onSearch="onSearch"></filter-form>
          </v-col>
        </v-row>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import FilterForm from './FilterForm'

export default {
  name: 'CommonTable',
  components: { FilterForm },
  props: {
    headers: {
      type: Array,
      default () {
        return []
      },
    },
    table_options: {
      type: Object,
      default () {
        return {
          itemsPerPage: 10,
          itemsPerPageOptions: [10, 20, 100],
          page: 1,
        }
      },
    },
    api: {
      type: Function,
      default () {
        return null
      },
    },
    filters: {
      type: Array,
      default () {
        return []
      },
    },
    image_columns: {
      type: Array,
      default () {
        return []
      },
    },
    chip_columns: {
      type: Array,
      default () {
        return []
      },
    },
    bool_columns: {
      type: Array,
      default () {
        return []
      },
    },
    header_actions: {
      type: Array,
      default () {
        return []
      },
    },
    actions: {
      type: Array,
      default () {
        return []
      },
    },
  },
  data () {
    return {
      totalDesserts: 0,
      desserts: [],
      loading: true,
      options: {
        itemsPerPage: 10,
        itemsPerPageOptions: [10, 20, 100],
        page: 1,
        sortBy: [],
        sortDesc: [],
      },
      params: {},
      table_filters: [],
      search_filters: [],
      dialog: false,
    }
  },
  watch: {
    '$route.query': {
      handler () {
        this.init()
      },
      deep: true,
    },
    options: {
      handler () {
        this.onHandle()
      },
      deep: true,
    },
  },
  computed: {
    pageCount () {
      return Math.ceil(this.totalDesserts / this.options.itemsPerPage)
    },
  },
  created () {
    this.table_filters = this.filters
    const { _limit, _skip, _orderBy, _orderByDesc, filters } = this.$route.query
    this.options.itemsPerPage = _limit ? Number(_limit) : 10
    this.options.page = _skip ? Math.floor(_skip / this.options.itemsPerPage) + 1 : 1
    if (_orderBy && _orderBy !== 'false') {
      this.options.sortBy = JSON.parse(_orderBy)
    }
    if (_orderByDesc) {
      this.options.sortDesc = JSON.parse(_orderByDesc)
    }
    if (filters) {
      this.search_filters = JSON.parse(filters)
      this.search_filters.forEach(value => {
        this.table_filters.forEach((val, ind) => {
          if (val.column === value.column && val.relation_name === value.relation_name) {
            this.table_filters[ind].value = value.value
          }
        })
      })
    }
  },
  mounted () {
    // this.options = this.table_options
    this.init()
  },
  methods: {
    onSearch (where) {
      this.search_filters = where
      this.onHandle()
      this.dialog = false
    },
    onHandle () {
      const { sortBy, sortDesc, page, itemsPerPage } = this.options
      let current_page = page ? page : 1
      let queryParams = {}
      queryParams['_limit'] = itemsPerPage
      queryParams['_skip'] = itemsPerPage * (current_page - 1)
      if (sortBy.length > 0) {
        queryParams['_orderBy'] = JSON.stringify(sortBy)
      }
      if (sortDesc.length > 0) {
        queryParams['_orderByDesc'] = JSON.stringify(sortDesc)
      }
      if (this.search_filters.length > 0) {
        queryParams['filters'] = JSON.stringify(this.search_filters)
      }

      this.$router.push({
        query: queryParams,
      })
    },
    onRefresh () {
      this.options.sortBy = []
      this.options.sortDesc = []
      this.search_filters = []
      this.params['filters'] = []

      this.$router.push({
        query: {},
      })
    },
    init () {
      const { sortBy, sortDesc, page, itemsPerPage } = this.options
      let current_page = page ? page : 1
      this.params['_limit'] = itemsPerPage
      this.params['_skip'] = itemsPerPage * (current_page - 1)
      if (sortBy) {
        this.params['_orderBy'] = sortBy
      }
      if (sortDesc) {
        this.params['_orderByDesc'] = sortDesc
      }
      if (this.search_filters.length > 0) {
        this.params['filters'] = this.search_filters.filter(val => {
          return !!val.value
        })
      }

      this.loading = true
      this.api(this.params).then(response => {
        this.loading = false
        this.desserts = response.data.items
        this.totalDesserts = response.data.total_count
      }).catch(reason => {
        console.log(reason)
        this.loading = false
      })
    },
    getLevelColor (value) {
      if (value < 1) {
        return 'red'
      }
      else if (value < 30) {
        return 'orange'
      }
      else {
        return 'green'
      }
    },
    getBoolColor (bool) {
      if (bool) {
        return 'red'
      }
      else {
        return 'green'
      }
    },
    getValveColor (value) {
      if (value === 0) {
        return 'green'
      }
      else {
        return 'red'
      }
    },
    getBoolValue (bool) {
      if (bool) {
        return '是'
      }
      else {
        return '否'
      }
    },
    onEmit (sign, item) {
      this.$emit('action', { sign: sign, item: item })
    },
    onSwitch(sign, item) {
      this.$emit('action', { sign: sign, item: item })
    }
  },
}
</script>

<style lang="scss" scoped>

</style>
