import qs from 'qs'
import request from '../utils/request'

export function me (params) {
  return request({
    url: 'me?' + qs.stringify(params),
    method: 'get'
  })
}

