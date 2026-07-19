<?php
$gosuslugiWidgetId = max(0, (int)get_info('gosuslugi_widget_id', 0));

if ($gosuslugiWidgetId === 0) {
    return;
}
?>
<script src='https://pos.gosuslugi.ru/bin/script.min.js'></script>

<style>
  #js-show-iframe-wrapper {
    background: linear-gradient(138.4deg, #38bafe 26.49%, #2d73bc 79.45%);
    color: #fff;
    cursor: pointer;
    min-width: 293px;
	height: 100%;
  }

  .pos-banner-btn_2 {
    background: #0d4cd3;
    color: #fff;
    border: none;
    border-radius: 8px;
    outline: 0;
    width: 240px;
    min-height: 56px;
    font-size: 18px;
    line-height: 24px;
  }

  .pos-banner-btn_2:hover {
    background: #1d5deb;
  }

  .pos-banner-btn_2:focus,
  .pos-banner-btn_2:active {
    background: #2a63ad;
  }

  /* Анимация */
  @keyframes fadeInFromNone {
    0% { display: none; opacity: 0 }
    1% { display: block; opacity: 0 }
    100% { display: block; opacity: 1 }
  }

  /* Шрифты (оставлены оригинальные) */
  @font-face{font-family:LatoWebLight;src:url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Light.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Light.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Light.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:LatoWeb;src:url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Regular.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Regular.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Regular.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:LatoWebBold;src:url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Bold.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Bold.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Bold.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:RobotoWebLight;src:url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Light.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Light.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Light.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:RobotoWebRegular;src:url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Regular.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Regular.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Regular.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:RobotoWebBold;src:url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Bold.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Bold.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Bold.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:ScadaWebRegular;src:url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Regular.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Regular.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Regular.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:ScadaWebBold;src:url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Bold.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Bold.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Bold.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:Geometria;src:url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria.eot);src:url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria.eot?#iefix) format("embedded-opentype"),url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria.ttf) format("truetype");font-weight:400;font-style:normal}@font-face{font-family:Geometria-ExtraBold;src:url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria-ExtraBold.eot);src:url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria-ExtraBold.eot?#iefix) format("embedded-opentype"),url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria-ExtraBold.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria-ExtraBold.ttf) format("truetype");font-weight:900;font-style:normal}
</style>

<div id="js-show-iframe-wrapper" class="d-flex align-items-center justify-content-center w-100">
  <div class="container-fluid p-0">
    <div class="row g-0 align-items-stretch">
      <!-- Логотип и слоган -->
      <div class="col-md-4 d-flex align-items-center justify-content-center p-4 p-lg-5">
        <div class="text-center">
          <img class="img-fluid mb-2" src="https://pos.gosuslugi.ru/bin/banner-fluid/gosuslugi-logo.svg" alt="Госуслуги" width="180" />
          <div class="fs-3 fw-bold">Решаем вместе</div>
        </div>
      </div>

      <!-- Контент и кнопка -->
      <div class="col-md-8 d-flex flex-column justify-content-center p-4 p-lg-5">
        <div class="mb-4">
          <div class="fs-4 mb-2">Не убран снег, яма на дороге, не горит фонарь?</div>
          <div class="fs-5">Столкнулись с проблемой — сообщите о ней!</div>
        </div>

        <div>
          <!-- pos-banner-btn_2 не удалять; другие классы не добавлять -->
          <button class="pos-banner-btn_2 btn fw-bold w-100" type="button">Сообщить о проблеме</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    "use strict";

    // Вспомогательные функции
    function ownKeys(e, t) {
      var o = Object.keys(e);
      if (Object.getOwnPropertySymbols) {
        var n = Object.getOwnPropertySymbols(e);
        if (t) n = n.filter(function(t) {
          return Object.getOwnPropertyDescriptor(e, t).enumerable
        });
        o.push.apply(o, n);
      }
      return o;
    }

    function _objectSpread(e) {
      for (var t = 1; t < arguments.length; t++) {
        var o = null != arguments[t] ? arguments[t] : {};
        if (t % 2) ownKeys(Object(o), true).forEach(function(t) {
          _defineProperty(e, t, o[t]);
        });
        else if (Object.getOwnPropertyDescriptors) Object.defineProperties(e, Object.getOwnPropertyDescriptors(o));
        else ownKeys(Object(o)).forEach(function(t) {
          Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(o, t));
        });
      }
      return e;
    }

    function _defineProperty(e, t, o) {
      if (t in e) Object.defineProperty(e, t, {
        value: o,
        enumerable: true,
        configurable: true,
        writable: true
      });
      else e[t] = o;
      return e;
    }

    // Основная логика
    var POS_PREFIX_1 = "--pos-banner-fluid-1__",
        posOptionsInitial = {
          "grid-template-columns": "100%",
          "grid-template-rows": "310px auto",
          "decor-grid-column": "initial",
          "decor-grid-row": "initial",
          "decor-padding": "30px 30px 0 30px",
          "decor-bg-position": "center calc(100% - 10px)",
          "decor-bg-size": "75% 75%",
          "content-padding": "0 30px 30px 30px",
          "slogan-font-size": "24px",
          "slogan-line-height": "32px"
        };

    function setStyles(e, t) {
      Object.keys(e).forEach(function(o) {
        t.style.setProperty(POS_PREFIX_1 + o, e[o]);
      });
    }

    function removeStyles(e, t) {
      Object.keys(e).forEach(function(e) {
        t.style.removeProperty(POS_PREFIX_1 + e);
      });
    }

    function changePosBannerOnResize() {
      var e = document.documentElement,
          t = _objectSpread({}, posOptionsInitial),
          o = document.getElementById("js-show-iframe-wrapper"),
          n = o ? o.offsetWidth : document.body.offsetWidth;

      if (n > 500) {
        t["grid-template-columns"] = "min-content 1fr";
        t["grid-template-rows"] = "100%";
        t["decor-grid-column"] = "2";
        t["decor-grid-row"] = "1";
        t["decor-padding"] = "30px 30px 30px 0";
        t["decor-bg-position"] = "calc(30% - 10px) calc(72% - 2px)";
        t["decor-bg-size"] = "calc(23% + 150px)";
        t["content-padding"] = "30px";
      }

      if (n > 800) {
        t["decor-bg-position"] = "calc(38% - 50px) calc(6% - 0px)";
        t["decor-bg-size"] = "420px";
        t["slogan-font-size"] = "32px";
        t["slogan-line-height"] = "40px";
      }

      if (n > 1020) {
        t["decor-bg-position"] = "calc(30% - 28px) calc(0% - 40px)";
        t["decor-bg-size"] = "620px";
      }

      setStyles(t, e);
    }

    // Инициализация
    changePosBannerOnResize();
    window.addEventListener("resize", changePosBannerOnResize);

    window.onunload = function() {
      var e = document.documentElement;
      window.removeEventListener("resize", changePosBannerOnResize);
      removeStyles(posOptionsInitial, e);
    };
  })();
</script>

<script>
	Widget("https://pos.gosuslugi.ru/form", <?=$gosuslugiWidgetId?>);
</script>
