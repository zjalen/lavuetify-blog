import qs from 'qs';
import request from '../utils/request'

export function login () {
    return request({
        url: '/login',
        method: 'post',
    })
}

export function logout() {
  return request({
    url: '/logout',
    method: 'post',
  })
}
