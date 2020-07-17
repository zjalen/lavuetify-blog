import request from '../utils/request'
import qs from 'qs'

/**
 * 获取用户信息
 */
export function getUsers(params) {
  return request({
    url: '/users?' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 获取单个用户信息
 * @param id
 * @returns {AxiosPromise}
 */
export function getUser (id) {
  return request({
    url: '/users/' + id,
    method: 'get',
  })
}

/**
 * 修改用户
 * @param id
 * @param params
 * @returns {AxiosPromise}
 */
export function updateUser(id, params) {
  return request({
    url: '/users/' + id,
    method: 'put',
    params,
  })
}
