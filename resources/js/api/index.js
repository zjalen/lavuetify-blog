import qs from 'qs'
import request from '../utils/request'

/**
 * 获取菜单信息
 */
export function getCategories() {
  return request({
    url: '/categories',
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
 * 获取 markdown 文章详情
 * @param id
 */
export function getArticleMD(id) {
  return request({
    url: '/markdown_files/' + id + '.md',
    method: 'get',
  })
}

/**
 * 获取 json 数据
 * @param url
 */
export function getJsonData(url) {
  return request({
    url: url,
    method: 'get',
  })
}
