import request from '../utils/request'
import qs from 'qs'

/**
 * 获取标签
 * @param params
 * @returns {AxiosPromise}
 */
export function getTags(params) {
  return request({
    url: '/tags?' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 新建标签
 * @param params
 */
export function createTag(params) {
  return request({
    url: '/tags',
    method: 'post',
    params
  })
}

/**
 * 删除标签
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteTag (id) {
  return request({
    url: '/tags/' + id,
    method: 'delete',
  })
}

/**
 * 修改标签
 * @param id
 * @param params
 * @returns {AxiosPromise}
 */
export function updatetTag (id, params) {
  return request({
    url: '/tags/' + id,
    method: 'put',
    params
  })
}
