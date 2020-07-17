import request from '../utils/request'

export function getAdminUserInfo () {
  return request({
    url: '/me?',
    method: 'get'
  })
}

export function updateAdminUserInfo (params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/changeUserInfo',
    method: 'post',
    data: params,
    config
  })
}

export function resetPassword (params) {
  return request({
    url: '/resetPassword',
    method: 'post',
    params
  })
}

