export default function ({ req, route, redirect }) {
  // const isClient = process.client
  const explorer = process.server ? req.headers['user-agent'] : navigator.userAgent
  if (!route.path.includes('ie-not-support')) {
    if (explorer.includes('MSIE')) { // ie10及以下
      return redirect(
        process.env.SERVER_DOMIN +
        '/ie-not-support')
    }
    // else if (explorer.includes('Firefox')) { // Firefox
    //   browser = 'Firefox'
    // } else if (explorer.includes('Chrome')) { // Chrome
    //   return context.redirect('http://www.jalen.top/ie-not-support')
    // } else if (explorer.includes('Opera')) { // Opera
    //   browser = 'Opera'
    // } else if (explorer.includes('Safari')) { // Safari
    //   browser = 'Safari'
    // } else if (explorer.includes('Trident/7.0')) { // IE11
    //   browser = 'IE:10.0以上'
    // }
  }
}
