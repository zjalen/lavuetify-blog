<template>
  <v-form>
    <v-row>
      <v-col
        cols="12"
        md="8"
        lg="9"
        style="overflow-y: scroll"
      >
        <v-text-field
          label="名称"
          outlined
          v-model="category.name"
        >
        </v-text-field>
      </v-col>
    </v-row>
  </v-form>
</template>

<script>
export default {
  name: 'CategoryCreateAndEdit',
  data:() => ({
    category: {
      
    }
  })
}
</script>

<style scoped>

</style>
