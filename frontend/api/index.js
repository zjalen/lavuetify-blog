import qs from 'qs'
export default function ($axios) {
  return {
    getCategories: () => {
      return $axios.get('/categories')
    },
    getArticles: (params) => {
      return $axios.get('/articles?' + qs.stringify(params))
    },
    getArticle: (id) => {
      return $axios.get('/articles/' + id)
    },
    getPage: (name) => {
      return $axios.get('/pages/' + name)
    },
    getLinks: () => {
      return $axios.get('/links')
    },
    logout: (type, accessToken) => {
      $axios.setHeader('access_token', accessToken)
      $axios.setHeader('type', type)
      return $axios.get('/oauth/logout')
    },
    getUserInfoByAccessToken: (type, accessToken) => {
      $axios.setHeader('access_token', accessToken)
      $axios.setHeader('type', type)
      return $axios.get('/oauth/me')
    }
  }
}
