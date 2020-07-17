import request from '../utils/request'
import qs from 'qs'

/**
 * 获取用户信息
 */
export function getAdminUsers(params) {
  return request({
    url: '/admin-users?' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 获取单个用户信息
 * @param id
 * @returns {AxiosPromise}
 */
export function getAdminUser (id) {
  return request({
    url: '/admin-users/' + id,
    method: 'get',
  })
}

/**
 * 修改用户信息
 * @param id
 * @param params
 * @returns {AxiosPromise}
 */
export function updateAdminUser(id, params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/admin-users/' + id,
    method: 'post',
    data: params,
    config
  })
}

export function createAdminUser(params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/admin-users',
    method: 'post',
    data: params,
    config
  })
}

/**
 * 删除用户
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteAdminUser(id) {
  return request({
    url: '/admin-users/' + id,
    method: 'delete'
  })
}
