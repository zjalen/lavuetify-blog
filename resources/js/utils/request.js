import axios from 'axios'
import store from '@/store'
import router from '@/router'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  console.error(
    'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

let my_base_host = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port : '')
// create an axios instance
const service = axios.create({
  // baseURL: process.env.VUE_APP_API_URL, // url = base url + request url
  baseURL: my_base_host + '/api/admin',
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 10000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent
    // store.commit('setLoading', true)
    if(store.getters.token) {
      config.headers['Authorization'] = `Bearer ${store.getters.token}`
    }
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
   */

  /**
   * Object
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    // store.commit('setLoading', false)
    return response.data
  },
  error => {
    console.log(error.response.data) // for debug
    let message = Object.prototype.hasOwnProperty.call(error.response.data, 'error_message')
      ? error.response.data.error_message
      : error.response.statusText
    if(error.response.status === 401) {
      message = '登录失效，请重新登录'
      store.commit('setToken', null)
      setTimeout(()=> {
        router.push({name: 'login'})
      }, 1500)
    }
    store.commit('setSnackbar', {
      message: message,
      color: 'error',
      timeout: 5000,
      show: true
    })
    return Promise.reject(error.response.data)
  }
)

export default service
