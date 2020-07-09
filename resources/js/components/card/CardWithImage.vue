<template>
  <v-card
  >
    <v-img
      class="white--text align-end"
      height="200px"
      :src="item.img_src ? item.img_src : default_img"
    >
      <v-row align="end" class="light-box white--text pa-2 fill-height">
        <v-col>
          <div class="headline">{{item.title}}</div>
        </v-col>
      </v-row>
    </v-img>

    <v-card-subtitle class="pb-0">{{item.subtitle}}</v-card-subtitle>

    <v-card-text>

      <div>{{item.description}}</div>
    </v-card-text>

    <v-card-actions class="justify-end">
      <v-btn
        v-for="(action, k) in item.actions"
        :key="k"
        :color="action.color ? action.color: 'primary'"
        outlined
        @click="onClick(item)"
      >
        {{action.text}}
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import community_img from '@/assets/images/community.jpg'

export default {
  name: 'CardWithImage',
  props: {
    item: {
      type: Object,
      default(){ 
        return  {
          id: null,
          color: 'primary',
          title: 'title',
          subtitle: '10000',
          icon: 'home',
          img_src: community_img,
        }
      },
    },
  },
  data () {
    return {
      default_img: community_img,
    }
  },
  methods: {
    onClick (item) {
      this.$emit('onCardClick', { unit_id: item.id })
    },
  },
}
</script>

<style scoped>
  .light-box {
    box-shadow: 0 0 20px inset rgba(0, 0, 0, .3);
    background-image: linear-gradient(to top, rgba(0, 0, 0, 1) 0%, transparent 60px);
  }
</style>
