<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>该页面不支持此浏览器 | Jalen 的博客</title>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .b-content {
                width: 700px;
                margin: 60px auto;
            }

            #nav {
                height: 200px;
                text-align: center;
            }

            #nav ul {
                list-style: none;
                line-height: 40px;
                margin-left: 5px;
            }

            #nav li {
                display: block;
                float: left;
            }

            #nav a {
                display: block;
                text-decoration: none;
            }

            #nav a:hover {
                background-color: lightblue;
            }

            .text-box {
                margin-top: 50px;
            }

            .text-box .list li a {
                float: left;
                width: 100px;
                height: 100px;
                margin-top: 20px
                text-align: center;
                color: #666;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 20px;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .hint {
                color: red;
                font-size: 18px
            }

            .iconfont{
                font-family:"iconfont" !important;
                font-size:35px !important;
                font-style:normal;
                -webkit-font-smoothing: antialiased;
                -webkit-text-stroke-width: 0.2px;
                -moz-osx-font-smoothing: grayscale;
            }
        </style>
        <link rel="stylesheet" href="//at.alicdn.com/t/font_1819597_v2kja62enrn.css">
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">

                </div>
                <div class="b-content">
                    <div class="pic-box">
                        <img src="/images/ie-not-support.png">
                        <p class="hint">为了最佳预览体验，我们的页面不支持 IE10 及以下的古老浏览器的访问，给您推荐以下现代高级浏览器。</p>
                    </div>
                    <div class="text-box">
                        <div class="title">点击可下载官方正版浏览器</div>
                        <div class="list" id="nav">
                            <ul>
                                <li>
                                    <a href="https://www.google.cn/intl/zh-CN/chrome" target="_blank">
                                        <i class="iconfont icon-chrome"></i><br>
                                        Chrome<br>
                                        <span>谷歌</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://browser.360.cn/ee/" target="_blank">
                                        <i class="iconfont icon-liulanqi-jisu"></i><br>
                                        360<br>
                                        <span>360</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://ie.sogou.com/" target="_blank">
                                        <i class="iconfont icon-sougou"></i><br>
                                        sougou<br>
                                        <span>搜狗</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.mozilla.org/zh-CN/firefox/new" target="_blank">
                                        <i class="iconfont icon-firefox"></i><br>
                                        Firefox<br>
                                        <span>火狐</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.microsoftedgeinsider.com" target="_blank">
                                        <i class="iconfont icon-edge"></i><br>
                                        Edge<br>
                                        <span>微软</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.opera.com/zh-cn" target="_blank">
                                        <i class="iconfont icon-opera"></i><br>
                                        Opera<br>
                                        <span>欧朋</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="copyright">
                            ©2020 Jalen 的博客
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
