import axios from 'axios'
// import store from '../store'

// create an axios instance
const service = axios.create({
  baseURL: 'api', // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 5000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent
    // store.commit('setLoading', true)
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
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    const res = response.data
    // store.commit('setLoading', false)
    return res;
  },
  error => {
    console.log('err' + error) // for debug
    // store.commit('setLoading', false)
    return Promise.reject(error)
  }
)

export default service
