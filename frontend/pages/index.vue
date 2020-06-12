<template>
  <v-row dense>
    <v-col v-if="breadcrumbs.length > 1" cols="12">
      <v-breadcrumbs :items="breadcrumbs" class="pa-0" />
    </v-col>
    <v-col v-if="filters.topic || filters.tag" cols="12" class="pb-3">
      <v-btn
        v-if="filters.topic"
        small
        class="mr-3"
        outlined
        color="tertiary"
        @click="$router.push({name: '/'})"
      >
        <v-icon left>
          mdi-file-document
        </v-icon>
        {{ filters.topic }}
        <v-icon right>
          mdi-close
        </v-icon>
      </v-btn>
      <v-btn v-if="filters.tag" small outlined color="info" @click="$router.push({name: '/'})">
        <v-icon left>
          mdi-tag-outline
        </v-icon>
        {{ filters.tag }}
        <v-icon right>
          mdi-close
        </v-icon>
      </v-btn>
    </v-col>
    <v-col v-for="(article, i) in articles" :key="i" cols="12">
      <v-skeleton-loader
        ref="skeleton"
        height="200"
        :loading="loading"
        type="article, actions"
        class="mx-auto"
      >
        <v-card outlined hover @click="toArticle(article.categoryId, article.id)">
          <div class="d-flex flex-column flex-sm-row">
            <v-avatar class="ma-3 image-box" :size="imageHeight" height="175px" tile>
              <v-img :src="article.cover_full_path" />
            </v-avatar>

            <v-row dense>
              <v-col :cols="12" class="pt-3">
                <div class="headline px-4">
                  <span v-if="article.is_top" class="error--text">[TOP]</span>
                  {{ article.title }}
                </div>

                <v-card-subtitle v-text="article.description" />
                <v-card-text class="text--primary">
                  <div class="d-flex flex-row">
                    <v-icon class="body-2" left>
                      mdi-clock-outline
                    </v-icon>
                    {{ article.created_at }}
                  </div>
                </v-card-text>

                <v-card-actions class="d-flex flex-sm-row flex-column-reverse align-start">
                  <v-btn
                    small
                    class="ma-2"
                    color="secondary"
                    outlined
                    @click.stop="onCateClick(article.category.name)"
                  >
                    <v-icon left>
                      mdi-menu
                    </v-icon>
                    分类：{{ article.category.name }}
                  </v-btn>
                  <v-spacer />
                  <v-btn
                    v-if="article.topic"
                    small
                    class="ma-2"
                    color="tertiary"
                    outlined
                    @click.stop="onTopicClick(article.topic.name)"
                  >
                    <v-icon left>
                      mdi-file-document
                    </v-icon>
                    主题：{{ article.topic.name }}
                  </v-btn>
                </v-card-actions>
              </v-col>
            </v-row>
          </div>
        </v-card>
      </v-skeleton-loader>
    </v-col>

    <v-col cols="12">
      <v-card v-if="articles.length < 1" outlined>
        <v-card-title class="headline">
          没找到相关文章，请您查阅其他内容。
        </v-card-title>
      </v-card>
      <v-pagination
        v-if="total_count > perPageCount"
        v-model="page"
        :length="pageCount"
        color="secondary"
        total-visible="10"
        @input="onPageChange"
      />
    </v-col>
  </v-row>
</template>
<script>

export default {
  watchQuery: ['page', 'category', 'topic', 'tag'],
  // fetch 与 asyncData 仅在 pages 下有效， component 中无效
  async fetch ({ store, app, query }) {
    return await app.$api.getCategories()
      .then((res) => {
        const categories = res.data.data.items
        store.dispatch('actionSetMenus', categories)
        store.dispatch('actionSetCurrentMenu', query.category)
      })
  },
  async asyncData ({ app, query }) {
    const perPageCount = 10
    const filters = []
    filters.push({
      sign: 'relation',
      relation_name: 'category',
      column: 'name',
      value: query.category
    })
    filters.push({
      sign: 'relation',
      relation_name: 'topic',
      column: 'name',
      value: query.topic
    })
    filters.push({
      sign: 'relation',
      relation_name: 'tags',
      column: 'name',
      value: query.tag
    })
    const params = {
      _limit: perPageCount,
      _skip: (Number(query.page) - 1) * perPageCount,
      _orderByDesc: ['is_top', 'created_at'],
      filters
    }
    const page = query.page ? Number(query.page) : 1
    const res = await app.$api.getArticles(params)
    // eslint-disable-next-line camelcase
    const { items, total_count } = res.data.data
    const pageCount = Math.ceil(
      // eslint-disable-next-line camelcase
      total_count / perPageCount
    )
    const breadcrumbs = [
      {
        disabled: false,
        to: '/',
        text: '首页',
        nuxt: true
      }
    ]
    if (query.category) {
      breadcrumbs.push({
        disabled: true,
        to: '/?category=' + query.category,
        text: query.category,
        nuxt: true
      })
    }
    return { articles: items, total_count, perPageCount, pageCount, page, breadcrumbs }
  },
  data: () => ({
    items: [],
    breadcrumbs: [],
    loading: false,
    total_count: 0,
    articles: [
      {
        category: {
          id: null,
          name: ''
        },
        categoryId: null,
        cover: '',
        created_at: '',
        description: '',
        id: '',
        is_top: false,
        title: '',
        topic: {
          id: null,
          name: ''
        },
        topic_id: null
      }
    ],
    page: 1,
    pageCount: 0,
    perPageCount: 10,
    topic_show: false,
    tag_show: false,
    filters: {
      topic: null,
      tag: null
    }
  }),
  computed: {
    imageHeight () {
      switch (this.$vuetify.breakpoint.name) {
        case 'xs':
          return 'auto'
      }
      return '175'
    }
  },
  mounted () {
    this.loading = false
  },
  methods: {
    onPageChange (page) {
      const currentPage = this.$route.query.page ? Number(this.$route.query.page) : 1
      if (currentPage !== page) {
        const rt = {}
        rt.path = '/'
        rt.query = {}
        rt.query.page = page
        this.$router.push(rt)
      }
    },
    onCateClick (categoryName) {
      const rt = {}
      rt.path = '/'
      rt.query = {}
      rt.query.category = categoryName
      this.$router.push(rt)
    },
    onTopicClick (topicName) {
      const rt = {
        path: '/',
        query: {
          topic: topicName
        }
      }
      this.$router.push(rt)
    },
    toArticle (cateId, articleId) {
      this.$router.push({
        path: '/articles/' + articleId
      })
    }
  }
}
</script>
<style scoped>
  .image-box {
    border: 1px solid rgba(200, 200, 200, .5);
  }
</style>
