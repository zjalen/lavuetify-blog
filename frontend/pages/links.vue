<template>
  <div class="content">
    <div class="head">
      友情链接
    </div>
    <hr>
    <v-row>
      <v-col
        v-for="(link, index) in links"
        :key="index"
        :xs="12"
        :sm="6"
        :md="4"
      >
        <v-card
          outlined
          flat
          hover
          :href="link.url"
          class="secondary--text hover-card"
        >
          <v-card-title>{{ link.name }}</v-card-title>
          <v-card-text>{{ link.description }}</v-card-text>
          <v-card-text>{{ link.url }}</v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  // fetch 与 asyncData 仅在 pages 下有效， component 中无效
  async fetch ({ store, app }) {
    return await app.$api.getCategories()
      .then((res) => {
        const categories = res.data.data.items
        store.dispatch('actionSetMenus', categories)
      })
  },
  async asyncData ({ app, store, payload }) {
    let links = null
    if (payload) {
      links = payload
    } else {
      const res = await app.$api.getLinks()
      links = res.data.data
    }
    store.dispatch('actionSetCurrentMenu', '友链')
    return {
      links
    }
  },
  data: () => ({
    links: [],
    title: '友情链接',
    description: '如果你想和我交换链接，可以给我留言'
  }),
  head () {
    return {
      title: this.title,
      meta: [
        { hid: 'description', name: 'description', content: this.description },
        { hid: 'author', name: 'author', content: 'Jalen 张佳林' },
        { hid: 'keywords', name: 'keywords', content: 'developer,ios,java,android,html,vue' }
      ]
    }
  }
}
</script>

<style lang="scss" scoped>
  .hover-card:hover {
    background: var(--v-secondary-lighten1);
    color: #fff;
    transition: ease-in-out all .3s;
    transform: translate3d(0, -2px, 0);
    -webkit-transform: translate3d(0, -3px, 0);
    .v-card__title {
      color: #fff;
    }
  }
</style>
