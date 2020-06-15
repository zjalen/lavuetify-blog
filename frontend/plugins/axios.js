// 引入自定义 api 请求链接
import api from '../api'

let axios = null

export default function ({ $axios, redirect }, inject) {
  $axios.onRequest((config) => {
    console.log('Making request to ' + config.url)
  })

  $axios.onError((error) => {
    const code = parseInt(error.response && error.response.status)
    if (code === 400) {
      redirect('/400')
    }
  })

  // Create a custom axios instance
  axios = $axios.create({
    headers: {
      common: {
        Accept: 'text/plain, */*'
      }
    }
  })

  // Set baseURL to something different
  axios.setBaseURL(process.env.AXIOS_URL_PREFIX)

  // Inject to context as $api
  inject('api', api(axios))
}
