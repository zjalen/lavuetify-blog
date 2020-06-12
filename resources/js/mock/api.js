// import Mock from 'mockjs'
import store from '../store'

export default [
  {
    url: '/articles\\?',
    type: 'get',
    response: config => {
      const { importance, type, title, page = 1, limit = 20, sort, category_id, tag_name, topic_name } = config.query

      let articles = store.getters.articles;
      let mockList = articles.filter(item => {
        if (importance && item.importance !== +importance) return false
        if (type && item.type !== type) return false
        if (item.is_hidden) return false
        if (title && item.title.indexOf(title) < 0) return false
        if (topic_name && item.topic) { 
          if (item.topic.name === topic_name) {
            return true 
          }
          return false
        }
        if (tag_name && item.tags.indexOf(tag_name) < 0) return false
        if (category_id && Number(item.category_id) !== Number(category_id)) return false
        return true
      })

      let topList = [];
      let otherList = [];
      mockList.forEach(item => {
        if (item.is_top) return topList.push(item);
        else return otherList.push(item);
      })

      mockList = topList.concat(otherList);

      if (sort === '-id') {
        mockList = mockList.reverse()
      }

      const pageList = mockList.filter((item, index) => index < limit * page && index >= limit * (page - 1))

      return {
        code: 200,
        data: {
          count: mockList.length,
          articles: pageList,
          cate_name: "",
          tag_name: "",
          topic_name: "",
        }
      }
    }
  },

  {
    url: '/menus',
    type: 'get',
    response: () => {
      let categories = store.getters.categories;
      let pages = store.getters.pages;
      let menus = categories.concat(pages);
      store.commit('setMenus', menus)
      return {
        code: 200,
        data: menus
      }
    }
  },

  {
    url: '/articles/\\d',
    type: 'get',
    response: config => {
      let { id } = config.query
      let parmas = null;
      for (let index in config.query) {
        if (index.indexOf("articles") > -1) {
          parmas = index.split('/');
          break;
        }
      }
      id = parmas[parmas.length - 1];
      let articles = store.getters.articles;
      let data = {};
      articles.forEach((article, index) => {
        if (article.id === +id) {
          data = article;
          if (articles[index - 1]) {
            data.prev = {
              id: articles[index - 1].id,
              category_id: articles[index - 1].category_id,
              category: articles[index - 1].category,
              title: articles[index - 1].title,
            }
          }
          if (articles[index + 1]) {
            data.next = {
              id: articles[index + 1].id,
              category_id: articles[index + 1].category_id,
              category: articles[index + 1].category,
              title: articles[index + 1].title,
            }
          }
        }
      })
      return {
        code: 200,
        data: data
      }
    }
  },
]