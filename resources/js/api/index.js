import qs from 'qs'
import request from '../utils/request'

/**
 * 获取分类信息
 */
export function getCategories() {
  return request({
    url: '/categories',
    method: 'get',
  })
}

/**
 * 获取主题信息
 */
export function getTopics() {
  return request({
    url: '/topics',
    method: 'get',
  })
}

/**
 * 获取主题信息
 */
export function getTags() {
  return request({
    url: '/tags',
    method: 'get',
  })
}

/**
 * 获取文章列表
 * @param params
 */
export function getArticles(params) {
  return request({
    url: '/articles?&' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 获取文章统计数量
 */
export function getArticlesCount() {
  return request({
    url: '/articles/count',
    method: 'get',
  })
}


/**
 * 删除文章
 * @param id
 */
export function deleteArticle(id) {
  return request({
    url: '/articles/' + id,
    method: 'delete',
  })
}

/**
 * 更新文章
 * @param id
 * @param params
 */
export function updateArticle(id, params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/articles/' + id,
    method: 'post',
    data: params,
    config
  })
}

/**
 * 新建文章
 * @param params
 */
export function createArticle(params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/articles',
    method: 'post',
    data: params,
    config
  })
}

/**
 * 上传文章图片
 * @param params
 * @returns {AxiosPromise}
 */
export function uploadArticleImage (params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/articles/uploadImage',
    method: 'post',
    data: params,
    config
  })
}

/**
 * 删除文章图片
 * @param params
 * @returns {AxiosPromise}
 */
export function deleteArticleImage (params) {
  return request({
    url: '/articles/deleteImage',
    method: 'delete',
    params
  })
}

/**
 * 获取文章详情
 * @param id
 */
export function getArticle(id) {
  return request({
    url: '/articles/' + id,
    method: 'get',
  })
}

