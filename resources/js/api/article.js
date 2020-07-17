import request from '../utils/request'
import qs from 'qs'

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
 * 获取标签列表
 */
export function getArticlesTagList() {
  return request({
    url: '/articles/tags',
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
 * 获取文章详情
 * @param id
 */
export function getArticle(id) {
  return request({
    url: '/articles/' + id,
    method: 'get',
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
 * 获取分类信息
 * @param params
 * @returns {AxiosPromise}
 */
export function getCategories(params) {
  return request({
    url: '/articles/categories?' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 获取主题信息
 * @param params
 * @returns {AxiosPromise}
 */
export function getTopics(params) {
  return request({
    url: '/articles/topics?' + qs.stringify(params),
    method: 'get',
  })
}
