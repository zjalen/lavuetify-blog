<template>
  <v-card>
    <v-card-title>操作</v-card-title>
    <v-card-text>
      <v-form ref="form">
        <v-text-field
          label="名称"
          outlined
          :rules="nameRules"
          v-model="item.name"
        >
        </v-text-field>
        <v-text-field
          label="简述"
          outlined
          :rules="nameRules"
          v-model="item.description"
        >
        </v-text-field>
        <v-text-field
          label="url"
          outlined
          :rules="nameRules"
          v-model="item.url"
        >
        </v-text-field>
        <v-text-field
          label="排序"
          outlined
          :rules="numberRules"
          hint="数字越小越靠前，不填默认为 99"
          v-model="item.order"
        >
        </v-text-field>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer/>
      <v-btn class="warning mx-3" @click="onCancel">取消</v-btn>
      <v-btn class="primary" @click="onSubmit">提交</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>

export default {
  name: 'LinkCreateAndEdit',
  props: {
    current_item: {
      type: Object,
      default () {
        return {}
      }
    }
  },
  data: () => ({
    item: {},
    nameRules: [
      value => !!value || '必填'
    ],
    numberRules: [
      v => (v === undefined || v === null || /^\d+$/.test(v)) || '只能填写数字',
    ]
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
      let res = this.$refs.form.validate()
      if (res) {
        this.$emit('onSubmit', this.item)
      }
    },
  },
}
</script>

<style scoped>

</style>
