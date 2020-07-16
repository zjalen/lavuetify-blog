<template>
  <v-card>
    <v-card-title class="primary white--text">操作</v-card-title>
    <v-card-text>
      <v-form class="mt-4">
        <v-text-field
          label="名称"
          outlined
          v-model="item.name"
        >
        </v-text-field>
      </v-form>
    </v-card-text>
    <v-card-actions class="pa-4">
      <v-spacer/>
      <v-btn class="warning" @click="onCancel">取消</v-btn>
      <v-btn class="primary" @click="onSubmit">提交</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: 'TagCreateAndEdit',
  props: {
    current_item: {
      type: Object,
      default () {
        return {}
      }
    }
  },
  data: () => ({
    item: {}
  }),
  watch: {
    current_item() {
      this.item = this.current_item
    }
  },
  mounted() {
    this.item = this.current_item
  },
  methods: {
    onCancel () {
      this.$emit('onCancel')
    },
    onSubmit () {
      this.$emit('onSubmit', this.item)
    },
  },
}
</script>

<style scoped>

</style>
