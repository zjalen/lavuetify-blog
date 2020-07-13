<template>
  <v-form
    ref="form"
  >
    <v-row>
      <v-col
        cols="12"
        md="6"
        lg="3"
        v-for="(item, key) in filter_array"
        :key="key"
      >
        <v-text-field
          v-if="item.type === 'text'"
          v-model="item.value"
          :label="item.title"
        ></v-text-field>

        <v-select
          v-else-if="item.type === 'select'"
          v-model="item.value"
          :label="item.title"
          :items="item.options"
        ></v-select>

        <v-menu
          v-else-if="item.type === 'date'"
          v-model="item.date_show"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="item.value"
              :label="item.title"
              prepend-icon="mdi-calendar-range"
              readonly
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker color="primary" v-model="item.value" @input="item.date_show = false"></v-date-picker>
        </v-menu>
      </v-col>
    </v-row>
    <div class="d-flex justify-end">
      <v-btn class="mr-4 warning" @click="clear">清除</v-btn>
      <v-btn class="primary" @click="submit">搜索</v-btn>
    </div>
  </v-form>
</template>

<script>
export default {
  name: 'FilterForm',
  props: {
    filters: {
      type: Array,
      default () {
        return []
      },
    },
  },
  data () {
    return {
      filter_array: [],
    }
  },
  created () {
    this.filter_array = this.filters
  },
  methods: {
    submit () {
      let filters = this.filter_array.map((value) => {
        if (value.value) {
          let where = {}
          if (value.compare) {
            where['compare'] = value.compare
          }
          if (value.sign) {
            where['sign'] = value.sign
          }
          if (value.relation_name) {
            where['relation_name'] = value.relation_name
          }
          where['column'] = value.column
          where['value'] = value.value
          return where
        }
      })
      
      let params = filters.filter((val) => {
        return !!val
      })
      this.$emit('onSearch', params)
    },
    clear () {
      this.filter_array = this.filter_array.map((item) => {
        return {
          ...item,
          value: '',
        }
      })
      // this.$emit('onSearch', [])
    },
  },
}
</script>

<style scoped>

</style>
