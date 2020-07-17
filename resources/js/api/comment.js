import request from '../utils/request'
import qs from 'qs'

/**
 * 获取评论列表
 * @param params
 * @returns {AxiosPromise}
 */
export function getComments (params) {
  return request({
    url: '/comments?' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 删除评论
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteComment (id) {
  return request({
    url: '/comments/' + id,
    method: 'delete',
  })
}

/**
 * 修改评论
 * @param id
 * @param params
 * @returns {AxiosPromise}
 */
export function updateComment (id, params) {
  return request({
    url: '/comments/' + id,
    method: 'put',
    params
  })
}
