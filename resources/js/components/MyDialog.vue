<template>
    <v-dialog
        v-model="$store.getters.dialog.show"
        width="500"
    >
        <v-card>
            <v-card-title
                class="title primary white--text"
                primary-title
            >
                {{$store.getters.dialog.title}}
            </v-card-title>

            <v-card-text class="body-1 mt-4">
                {{$store.getters.dialog.text}}
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="warning"
                    @click="onClose"
                >
                    取消
                </v-btn>
                <v-btn
                    color="primary"
                    @click="onConfirm($store.getters.dialog.sign)"
                >
                    确定
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  export default {
    name: 'MyDialog',
    methods: {
      onConfirm(sign) {
        this.$VM.$emit('onDialogConfirm', sign);
      },
      onClose() {
        let dialog = this.$store.getters.dialog
        dialog.show = false;
        this.$store.commit('setDialog', dialog)
      }
    }
  }
</script>

<style scoped>
</style>
