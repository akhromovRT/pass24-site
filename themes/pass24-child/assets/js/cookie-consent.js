/**
 * PASS24 Cookie Consent Banner
 *
 * Информационный баннер о cookies (ФЗ-152).
 * Не блокирует аналитику — достаточно для РФ.
 * Cookie p24_cookie_consent хранится 365 дней.
 *
 * @package PASS24_Child
 */
(function () {
  'use strict';

  var COOKIE_NAME = 'p24_cookie_consent';
  var COOKIE_DAYS = 365;

  function getCookie(name) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : '';
  }

  function setCookie(name, value, days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 86400000));
    document.cookie = name + '=' + value +
      '; expires=' + date.toUTCString() +
      '; path=/; SameSite=Lax';
  }

  if (getCookie(COOKIE_NAME) === '1') return;

  document.addEventListener('DOMContentLoaded', function () {
    var banner = document.createElement('div');
    banner.className = 'p24-cookie-banner';

    var text = document.createElement('span');
    text.textContent = 'Мы используем файлы cookie для аналитики и улучшения работы сайта. Продолжая использование, вы соглашаетесь с нашей ';
    var link = document.createElement('a');
    link.href = '/privacy-policy/';
    link.textContent = 'политикой конфиденциальности';
    text.appendChild(link);
    text.appendChild(document.createTextNode('.'));

    var btn = document.createElement('button');
    btn.className = 'p24-cookie-banner__btn';
    btn.textContent = 'Принять';
    btn.addEventListener('click', function () {
      setCookie(COOKIE_NAME, '1', COOKIE_DAYS);
      banner.style.display = 'none';
    });

    banner.appendChild(text);
    banner.appendChild(btn);
    document.body.appendChild(banner);
  });
})();
