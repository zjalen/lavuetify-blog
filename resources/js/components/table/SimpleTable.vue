<template>
  <v-simple-table>
    <template v-slot:default>
      <thead>
      <tr>
        <th class="text-left"
            v-for="(item, key) in headers"
            :class="item.class"
            :key="key"
        >
          {{item.text}}
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(item, key) in table_data" :key="key">
        <td
          v-for="(column, k) in item"
          :key="k"
        >
          {{ column }}
        </td>
      </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>

<script>
export default {
  name: 'SimpleTable',
  props: {
    headers: {
      type: Array,
      default () {
        return []
      },
    },
    table_data: {
      type: Array,
      default () {
        return []
      },
    },
  },

}
</script>

<style scoped>

</style>
