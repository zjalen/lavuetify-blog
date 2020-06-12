<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- 360 等双核浏览器优先采用极速模式 -->
    <meta name="renderer" content="webkit"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @if($header) {{ $header }} | @endif {{ config('app.name', 'Jalen的博客') }}</title>
    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }} HTML,IOS,PHP,Android,Linux,MacOS">

    <!-- Styles -->
    <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
    <!-- 引入 md icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .mid-content {
            min-height: calc(100vh - 61px - 79px);
        }
        .header {
            height: 168px;
            background-color: rgba(0, 0, 0, 0) !important;
        }
        .footer {
            height: 123px;
        }
        .content {
            min-height: calc(100vh - 168px - 123px);
        }
    </style>
</head>

<body>
<div id="app">
    @yield('content')
</div>

<script src="{{ mix('js/app.js', 'assets') }}"></script>
<script>
  let ie = IEVersion()
  console.log(ie)
  if (ie <= 10) {
    window.location.href = '/ie-not-support'
  }

  /**
   * @return {number}
   */
  function IEVersion () {
    let userAgent = navigator.userAgent //取得浏览器的userAgent字符串
    let isIE = userAgent.indexOf('compatible') > -1 && userAgent.indexOf('MSIE') > -1 //判断是否IE<11浏览器
    let isEdge = userAgent.indexOf('Edge') > -1 && !isIE //判断是否IE的Edge浏览器
    let isIE11 = userAgent.indexOf('Trident') > -1 && userAgent.indexOf('rv:11.0') > -1
    if (isIE) {
      let reIE = new RegExp('MSIE (\\d+\\.\\d+);')
      reIE.test(userAgent)
      let fIEVersion = parseFloat(RegExp['$1'])
      console.log(fIEVersion)
      if (fIEVersion === 7) {
        return 7
      }
      else if (fIEVersion === 8) {
        return 8
      }
      else if (fIEVersion === 9) {
        return 9
      }
      else if (fIEVersion === 10) {
        return 10
      }
      else {
        return 6 //IE版本<=7
      }
    }
    else if (isEdge) {
      return 16 //edge
    }
    else if (isIE11) {
      return 11 //IE11
    }
    else {
      return 15 //不是ie浏览器
    }
  }
</script>
</body>

</html>
