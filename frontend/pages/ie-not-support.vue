<template>
  <div class="page">
    <h1>是时候升级你的浏览器了</h1>
    <p>你正在使用旧版 Internet Explorer（IE11以下版本或使用该内核的浏览器）。这意味着在升级浏览器前，你将无法访问此网站。</p>
    <div class="hr" />
    <h2>请注意：Windows XP 及旧版 Internet Explorer 的支持服务已终止</h2>
    <p>
      自2016年1月12日起，微软不再为 IE 11 以下版本提供相应支持和更新。没有关键的浏览器安全更新，您的电脑可能易受有害病毒、间谍软件和其他恶意软件的攻击，它们可以窃取或损害您的业务数据和信息。请参阅
      <a href="https://support.dmeng.net/end-of-ie-support.html">微软对旧版 Internet Explorer 的支持服务已终止的说明</a> 。
    </p>
    <div class="hr" />
    <h2>更先进的浏览器</h2>
    <p class="targetline">
      推荐使用以下浏览器的最新版本。如果你的电脑已有以下浏览器的最新版本则直接使用该浏览器极速模式访问
      <b id="referrer">
        <a href="http://www.jalen.top"> www.jalen.top </a>
      </b>即可。
    </p>
    <!-- before-browser -->
    <ul id="browser-list" class="browser-list">
      <li class="browser chrome">
        <a
          id="browser-chrome-link"
          href="https://www.google.cn/chrome/thank-you.html?standalone=1&amp;statcb=0&amp;installdataindex=empty"
        >
          <img src="/images/chrome.png" width="34" height="34" alt="谷歌浏览器">
          谷歌浏览器
          <span id="browser-chrome-badge">Google Chrome</span>
        </a>
      </li>
      <li class="browser firefox">
        <a
          id="browser-firefox-link"
          href="https://download.mozilla.org/?product=firefox-latest-ssl&amp;os=win&amp;lang=zh-CN"
        >
          <img src="/images/firefox.png" width="34" height="34" alt="火狐浏览器">
          火狐浏览器
          <span id="browser-firefox-badge">
            Mozilla Firefox
          </span>
        </a>
      </li>
      <li class="browser edge">
        <a id="browser-edge-link" href="https://www.microsoft.com/zh-cn/windows/microsoft-edge"><img
          src="/images/edge.png"
          width="34"
          height="34"
          alt="微软浏览器"
        >微软浏览器<span
          id="browser-edge-badge"
        >Microsoft Edge</span></a>
      </li>
      <li class="browser ie2345">
        <a id="browser-ie2345-link" href="https://dn-2345.cdn.bcebos.com/2345explorer/p8_k2345886_v2.0.exe"><img
          src="/images/ie2345.png"
          width="34"
          height="34"
          alt="2345浏览器"
        >2345加速浏览器<span
          id="browser-ie2345-badge"
        >双内核安全版</span></a>
      </li>
      <li class="browser se360">
        <a id="browser-se360-link" href="https://dl.360safe.com/netunion/20140425/360se%2b75526%2bn1abed0ce91.exe"><img
          src="/images/se360.png"
          width="34"
          height="34"
          alt="360安全浏览器"
        >360安全浏览器<span
          id="browser-se360-badge"
        >小白用户选择</span></a>
      </li>
      <li class="browser qqbrowser">
        <a
          id="browser-qqbrowser-link"
          href="https://rpcs.myapp.com/myapp/rcps/d/85000/QQBrowser_Setup_9.2.5170.402_9.2.exe?fsname=QQBrowser_subid@100002_urlid@100002.exe"
        ><img src="/images/qqbrowser.png" width="34" height="34" alt="QQ浏览器9">QQ浏览器9<span
          id="browser-qqbrowser-badge"
        >腾讯用户推荐</span></a>
      </li>
      <li class="browser clearleft" />
    </ul>
    <!-- after-browser -->
    <div class="hr" />
    <h2>为什么会出现这个页面？</h2>
    <p>如果你不知道升级浏览器是什么意思，请请教一些熟练电脑操作的朋友。如果你使用的不是IE6/7/8/9/10，而是360浏览器、QQ浏览器、搜狗浏览器等，出现这个页面是因为你使用的不是该浏览器的最新版本，升级至最新即可。</p>
    <div class="hr" />
    <h2>一起抵制IE6、IE7、IE8、IE9、IE10</h2>
    <p>
      为了兼容这个曾经的浏览器霸主，网页设计人员需要做大量的代码工作。对于普通用户而言，低版本IE更是一个岌岌可危的安全隐患，在Windows历史上几次大的木马病毒事件都是利用IE漏洞进行传播。所以，请和我们一起抵制IE的过期版本！
    </p>
    <div class="hr" />
    你是网站技术人员吗？请加入旧版 Internet Explorer 淘汰行动。<a href="https://support.dmeng.net/kill-old-versions-of-ie.html">详情</a>
  </div>
</template>

<script>
import '~/assets/ie-not-support.css'
export default {
  name: 'IeNotSupport',
  layout: 'empty'
}
</script>

<style lang="scss" scoped>
</style>
