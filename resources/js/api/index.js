import qs from 'qs'
import request from '../utils/request'

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
export function editCategory (id, params) {
  return request({
    url: '/categories/' + id,
    method: 'put',
    params
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
 * 删除主题
 * @param id
 * @returns {AxiosPromise}
 */
export function deleteTopic (id) {
  return request({
    url: '/topics/' + id,
    method: 'delete',
  })
}

/**
 * 修改主题
 * @param id
 * @param params
 * @returns {AxiosPromise}
 */
export function editTopic (id, params) {
  return request({
    url: '/topics/' + id,
    method: 'put',
    params
  })
}

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
export function editTag (id, params) {
  return request({
    url: '/tags/' + id,
    method: 'put',
    params
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
export function editComment (id, params) {
  return request({
    url: '/comments/' + id,
    method: 'put',
    params
  })
}

