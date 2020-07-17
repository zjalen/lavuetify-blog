import request from '../utils/request'
import qs from 'qs'

/**
 * 获取页面列表
 * @param params
 */
export function getPages(params) {
  return request({
    url: '/pages?&' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 删除页面
 * @param id
 */
export function deletePage(id) {
  return request({
    url: '/pages/' + id,
    method: 'delete',
  })
}

/**
 * 获取页面详情
 * @param id
 */
export function getPage(id) {
  return request({
    url: '/pages/' + id,
    method: 'get',
  })
}

/**
 * 更新页面
 * @param id
 * @param params
 */
export function updatePage(id, params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/pages/' + id,
    method: 'post',
    data: params,
    config
  })
}

/**
 * 新建页面
 * @param params
 */
export function createPage(params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/pages',
    method: 'post',
    data: params,
    config
  })
}

/**
 * 上传页面图片
 * @param params
 * @returns {AxiosPromise}
 */
export function uploadPageImage (params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/pages/uploadImage',
    method: 'post',
    data: params,
    config
  })
}

/**
 * 删除页面图片
 * @param params
 * @returns {AxiosPromise}
 */
export function deletePageImage (params) {
  return request({
    url: '/pages/deleteImage',
    method: 'delete',
    params
  })
}
