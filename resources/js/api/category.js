import request from '../utils/request'
import qs from 'qs'

/**
 * 获取分类信息
 * @param params
 * @returns {AxiosPromise}
 */
export function getCategories(params) {
  return request({
    url: '/categories?' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 新建分类
 * @param params
 */
export function createCategory(params) {
  return request({
    url: '/categories',
    method: 'post',
    params
  })
}

/**
 * 删除分类
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteCategory (id) {
  return request({
    url: '/categories/' + id,
    method: 'delete',
  })
}

/**
 * 修改分类
 * @param id
 * @param params
 * @returns {AxiosPromise}
 */
export function updateCategory (id, params) {
  return request({
    url: '/categories/' + id,
    method: 'put',
    params
  })
}
