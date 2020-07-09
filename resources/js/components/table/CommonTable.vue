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
            <v-spacer />
            <v-btn v-if="filters.length > 0" tile outlined class="mx-1 white--text" @click="dialog = true">
              搜索
              <v-icon right color="white">mdi-card-search-outline</v-icon>
            </v-btn>
          </v-col>
        </v-row>
      </template>
      <template v-for="(v,key) in level_columns" v-slot:[v]="{ item }">
        <v-chip :key="key" small :color="getLevelColor(item[v.split('.')[1]])" dark>{{ item[v.split('.')[1]]
          }}
        </v-chip>
      </template>
      <template v-for="(value,key) in bool_columns" v-slot:[value]="{ item }">
        <v-chip :key="key" small :color="getBoolColor(item[value.split('.')[1]])" dark>{{
          getBoolValue(item[value.split('.')[1]])
          }}
        </v-chip>
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
            <filter-form :filters="search_filters" @onSearch="onSearch"></filter-form>
          </v-col>
        </v-row>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import FilterForm from './FilterForm'
import qs from 'qs'

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
    level_columns: {
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
        _orderBy: null,
        _orderByDesc: null,
      },
      params: {},
      search_filters: [],
      dialog: false,
    }
  },
  watch: {
    options: {
      handler () {
        this.init()
      },
      deep: true,
    },
  },
  computed: {
    pageCount () {
      return Math.ceil(this.totalDesserts / this.options.itemsPerPage)
    },
  },
  mounted () {
    // this.search_filters = this.filters
    this.options = this.table_options
    const { _skip, _orderBy,_orderByDesc, filters } = this.$route.query
    this.options.page = _skip ? Math.floor(_skip / this.options.itemsPerPage) + 1 : 1
    this.options._orderBy = _orderBy ? _orderBy : null
    this.options._orderByDesc = _orderByDesc ? _orderByDesc : null
    if (filters) this.search_filters = qs.parse(filters)
  },
  methods: {
    onSearch (where) {
      this.search_filters = where
      this.init()
      this.dialog = false
    },
    init() {
      const { sortBy, sortDesc, page, itemsPerPage } = this.options
      let current_page = page ? page : 1
      this.params['_limit'] = itemsPerPage
      this.params['_skip'] = itemsPerPage * (current_page - 1)
      this.params['_orderBy'] = sortBy
      this.params['_orderByDesc'] = sortDesc
      this.params['filters'] = this.search_filters.filter(val => {
        return !!val.value
      })
      
      // TODO 完善 query 与查询条件的转换关联
      // this.$route.query = qs.stringify(this.params)
      // this.$router.push({ path: this.$route.path + '?' + queryString })
      
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
  },
}
</script>

<style lang="scss" scoped>

</style>
