<template>
  <v-overlay :absolute="false" :value="overlay" opacity="0.9">
    <img @click="hideImg" class="img-view" :src="imgSrc" />
    <v-btn class="icon-btn" color="primary" middle fab @click="hideImg">
      <v-icon>mdi-close</v-icon>
    </v-btn>
  </v-overlay>
</template>
<script>
export default {
  props: {
    imgSrc: {
      default: "",
      type: String
    },
    showImg: {
      default: false,
      type: Boolean
    }
  },
  data() {
    return {
      // overlay: false,
    };
  },
  computed: {
    overlay() {
      return this.showImg;
    }
  },
  methods: {
    hideImg() {
      // 发送事件
      this.$emit("hideImg");
    }
  }
};
</script>
<style scoped>
.img-view {
  position: relative;
  max-width: 90vw;
  max-height: 90vh;
}

.icon-btn {
  position: absolute;
  right: -30px;
  top: -30px;
}
</style>