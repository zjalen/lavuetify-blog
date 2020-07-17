import request from '../utils/request'
import qs from 'qs'

/**
 * 获取链接内容
 */
export function getLinks(params) {
  return request({
    url: '/links?' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 获取主题内容
 * @param id
 * @returns {AxiosPromise}
 */
export function getLink (id) {
  return request({
    url: '/links/' + id,
    method: 'get',
  })
}

/**
 * 删除链接
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteLink (id) {
  return request({
    url: '/links/' + id,
    method: 'delete',
  })
}

/**
 * 修改链接
 * @param id
 * @param params
 * @returns {AxiosPromise}
 */
export function updateLink(id, params) {
  return request({
    url: '/links/' + id,
    method: 'put',
    params,
  })
}

/**
 * 创建主题
 * @param params
 * @returns {never}
 */
export function createLink(params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/links',
    method: 'post',
    data: params,
    config
  })
}
