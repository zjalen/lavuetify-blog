<template>
    <v-row dense>
        <v-col v-if="breadcrumbs_show" cols="12">
            <v-breadcrumbs :items="breadcrumbs" class="pa-0"></v-breadcrumbs>
        </v-col>
        <v-col v-if="filters.topic || filters.tag" cols="12" class="pb-3">
            <v-btn
                small
                class="mr-3"
                v-if="filters.topic"
                outlined
                color="tertiary"
                @click="$router.push({name: '/'})"
            >
                <v-icon left>mdi-file-document</v-icon>
                {{filters.topic}}
                <v-icon right>mdi-close</v-icon>
            </v-btn>
            <v-btn small v-if="filters.tag" outlined color="info" @click="$router.push({name: '/'})">
                <v-icon left>mdi-tag-outline</v-icon>
                {{filters.tag}}
                <v-icon right>mdi-close</v-icon>
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
                <v-card outlined hover @click="toArticle(article.category_id, article.id)">
                    <div class="d-flex flex-column flex-sm-row">
                        <v-avatar class="ma-3 image-box" :size="imageHeight" height="175px" tile>
                            <v-img :src="article.cover_full_path"></v-img>
                        </v-avatar>

                        <v-row dense>
                            <v-col :cols="12" class="pt-3">
                                <div class="headline px-4">
                                    <span v-if="article.is_top" class="error--text">[TOP]</span>
                                    {{article.title}}
                                </div>

                                <v-card-subtitle v-text="article.description"></v-card-subtitle>
                                <v-card-text class="text--primary">
                                    <div class="d-flex flex-row">
                                        <v-icon class="body-2" left>mdi-clock-outline</v-icon>
                                        {{ article.created_at }}
                                    </div>
                                </v-card-text>

                                <v-card-actions class="d-flex flex-sm-row flex-column-reverse align-start">
                                    <v-btn
                                        small
                                        class="ma-2"
                                        color="secondary"
                                        outlined
                                        @click="onCateClick(article.category_id)"
                                    >
                                        <v-icon left>mdi-menu</v-icon>
                                        分类：{{ article.category.name }}
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        small
                                        class="ma-2"
                                        color="tertiary"
                                        outlined
                                        v-if="article.topic"
                                        @click="onTopicClick(article.topic.name)"
                                    >
                                        <v-icon left>mdi-file-document</v-icon>
                                        主题：{{article.topic.name}}
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
                <v-card-title class="headline">没找到相关文章，请您查阅其他内容。</v-card-title>
            </v-card>
            <v-pagination
                v-model="current_page"
                :length="page_count"
                color="secondary"
                total-visible="10"
                v-if="articles.length > 1"
                @input="onPageChange"
            ></v-pagination>
        </v-col>
    </v-row>
</template>
<script>
  import { getArticles } from '../../api'

  export default {
    props: {
      content_data: {
        type: Object,
        default() {
          return {}
        }
      },
    },
    data: () => ({
      items: [],
      breadcrumbs: [
        {
          disabled: false,
          href: '/#/',
          link: true,
          text: '首页',
        },
      ],
      loading: true,
      articles: [
        {
          category: { id: null, name: '' },
          category_id: null,
          cover: '',
          created_at: '',
          description: '',
          id: '',
          is_top: false,
          title: '',
          topic: { id: null, name: '' },
          topic_id: null,
        },
      ],
      current_page: 1,
      page_count: 0,
      per_page_count: 10,
      breadcrumbs_show: false,
      topic_show: false,
      tag_show: false,
      filters: {
        topic: null,
        tag: null,
      },
    }),
    mounted () {
      this.loading = false
      this.articles = this.content_data.items
      this.page_count = Math.ceil(
        this.content_data.total_count / this.per_page_count,
      )
      // if (this.$route.params.cate) {
      //   setTimeout(() => {
      //     let breadcrumb_name = null
      //     this.$store.getters.menus.filter(menu => {
      //       if (menu.id === Number(this.$route.params.cate)) {
      //         breadcrumb_name = menu.name
      //       }
      //     })
      //     this.breadcrumbs.push({
      //       disabled: true,
      //       href: '/#/cates/' + this.$route.params.cate,
      //       link: true,
      //       text: breadcrumb_name,
      //     })
      //   }, 500)
      // }
      // this.current_page = this.$route.query.page
      //   ? Number(this.$route.query.page)
      //   : 1
      // this.init()
    },
    computed: {
      imageHeight () {
        switch (this.$vuetify.breakpoint.name) {
          case 'xs':
            return 'auto'
        }
        return '175'
      },
    },
    methods: {
      init () {
        this.breadcrumbs_show = !!this.$route.params.cate
        this.filters.topic = this.$route.query.topic
        this.filters.tag = this.$route.query.tag
        let category_id = Number(this.$route.params.cate) === 0 ? null : Number(this.$route.params.cate)
        let filters = []
        if (category_id) {
          filters.push({
            sign: 'relation',
            relation_name: 'category',
            column: 'id',
            value: category_id,
          })
        }
        filters.push({
          sign: 'relation',
          relation_name: 'topic',
          column: 'name',
          value: this.$route.query.topic,
        })
        filters.push({
          sign: 'relation',
          relation_name: 'tags',
          column: 'name',
          value: this.$route.query.tag,
        })
        let params = {
          _limit: this.per_page_count,
          _skip: (this.current_page - 1) * this.per_page_count,
          _orderByDesc: ['is_top', 'created_at'],
          filters: filters,
        }
        getArticles(params).then(response => {
          setTimeout(() => {
            this.loading = false
            this.articles = response.data.items
            if (this.articles.length > 0) {
              this.page_count = Math.ceil(
                response.data.total_count / this.per_page_count,
              )
            }
          }, 500)
        })
      },
      onPageChange (page) {
        let rt = {}
        rt['name'] = this.$route.name === '/' ? 'index' : this.$route.name
        rt['query'] = {}
        rt['query']['page'] = page
        this.$router.push(rt)
      },
      onCateClick (category_id) {
        if (Number(this.$route.params.cate) === category_id) {
          return
        }
        let rt = {}
        rt['name'] = 'cate'
        rt['params'] = {}
        rt['params']['cate'] = category_id
        this.$router.push(rt)
      },
      onTopicClick (topic_name) {
        let rt = {
          name: '/',
          query: {
            topic: topic_name,
          },
        }
        this.$router.push(rt)
      },
      toArticle (cate_id, article_id) {
        this.$router.push({
          name: 'article',
          params: { cate: cate_id, id: article_id },
        })
      },
    },
  }
</script>
<style scoped>
    .image-box {
        border: 1px solid rgba(200, 200, 200, .5);
    }
</style>
