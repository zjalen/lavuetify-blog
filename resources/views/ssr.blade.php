<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <title>My server side rendered app</title>
      <!-- Styles -->
      <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
      <!-- 引入 md icons -->
      <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
      <script defer src="{{ mix('js/app-client.js', 'assets') }}"></script>
  </head>
  <body>
    {!! ssr('assets/js/app-server.js')->fallback('<div id="app"></div>')->render() !!}
  </body>
</html>
