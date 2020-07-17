import request from '../utils/request'
import qs from 'qs'

/**
 * 获取主题信息
 */
export function getTopics(params) {
  return request({
    url: '/topics?' + qs.stringify(params),
    method: 'get',
  })
}

/**
 * 获取主题内容
 * @param id
 * @returns {AxiosPromise}
 */
export function getTopic (id) {
  return request({
    url: '/topics/' + id,
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
export function updateTopic(id, params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/topics/' + id,
    method: 'post',
    data: params,
    config
  })
}

/**
 * 创建主题
 * @param params
 * @returns {never}
 */
export function createTopic(params) {
  const config = {
    headers: {
      'Content-Type': 'multipart/form-data;charset=UTF-8'
    }
  }
  return request({
    url: '/topics',
    method: 'post',
    data: params,
    config
  })
}
