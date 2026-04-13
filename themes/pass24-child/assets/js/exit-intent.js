/**
 * Exit-intent popup — PASS24
 * Triggers:
 *   1. mouseout to top of page (desktop) — only after DELAY_MS
 *   2. After DELAY_MS (15s) on all pages
 * Shows once per 7 days (cookie). Does NOT fire before delay.
 */
(function () {
  'use strict';

  var COOKIE_NAME  = 'p24_exit_shown';
  var COOKIE_DAYS  = 7;
  var DELAY_MS     = 15000;

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

  function alreadyShown() {
    return getCookie(COOKIE_NAME) === '1';
  }

  function markShown() {
    setCookie(COOKIE_NAME, '1', COOKIE_DAYS);
  }

  function isProductOrSolutionPage() {
    var p = window.location.pathname;
    return p.indexOf('/products/') === 0 || p.indexOf('/solutions/') === 0;
  }

  function el(tag, cls, txt) {
    var e = document.createElement(tag);
    if (cls) e.className = cls;
    if (txt !== undefined) e.textContent = txt;
    return e;
  }

  function buildDemoVariant(box, close) {
    var badge = el('div', 'p24-exit-popup__badge', 'Бесплатно');
    var title = el('h2', 'p24-exit-popup__title', 'Посмотрите PASS24 вживую');
    var text  = el('p', 'p24-exit-popup__text', '30-минутная демонстрация под ваш объект — без обязательств.');
    var cta   = el('a', 'p24-btn p24-btn-primary p24-exit-popup__cta', 'Записаться на демо');
    cta.href  = '/demo/';
    var skip  = el('button', 'p24-exit-popup__skip', 'Нет, спасибо');
    skip.addEventListener('click', close);
    box.appendChild(badge);
    box.appendChild(title);
    box.appendChild(text);
    box.appendChild(cta);
    box.appendChild(skip);
  }

  function buildChecklistVariant(box, close) {
    var badge = el('div', 'p24-exit-popup__badge', 'Бесплатный гайд');
    var title = el('h2', 'p24-exit-popup__title', 'Скачайте чек-лист бесплатно');
    var text  = el('p', 'p24-exit-popup__text', '15 пунктов для выбора облачной СКУД — экономия 200+ часов на поиске.');

    var form  = el('form', 'p24-exit-popup__form');
    var input = el('input', 'p24-form-input');
    input.type        = 'email';
    input.placeholder = 'Ваш email';
    input.required    = true;

    var btn   = el('button', 'p24-btn p24-btn-primary', 'Получить чек-лист');
    btn.type  = 'submit';

    var consentLabel = el('label', 'p24-form-consent');
    var consentBox   = document.createElement('input');
    consentBox.type  = 'checkbox';
    consentBox.name  = 'consent';
    consentBox.required = true;
    var consentSpan  = document.createElement('span');
    var consentLink  = document.createElement('a');
    consentLink.href = '/privacy/';
    consentLink.target = '_blank';
    consentLink.textContent = 'согласие на\u00A0обработку персональных данных';
    consentSpan.appendChild(document.createTextNode('Я\u00A0даю '));
    consentSpan.appendChild(consentLink);
    consentLabel.appendChild(consentBox);
    consentLabel.appendChild(consentSpan);

    form.appendChild(input);
    form.appendChild(consentLabel);
    form.appendChild(btn);

    var successDiv = el('div', 'p24-exit-popup__success');
    successDiv.style.display = 'none';
    var successText = el('p', '', 'Отправлено! Проверьте почту.');
    var successLink = el('a', 'p24-btn p24-btn-secondary', 'Открыть чек-лист');
    successLink.href   = '/resources/guide-choose-skud/';
    successLink.target = '_blank';
    successDiv.appendChild(successText);
    successDiv.appendChild(successLink);

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var email = input.value.trim();
      if (!email) return;
      btn.disabled    = true;
      btn.textContent = 'Отправляем...';

      var data = { email: email, source: 'exit_intent_checklist' };
      window.P24Analytics.enrichFormData(data, function (enriched) {
        fetch(wpApiSettings.root + 'pass24/v1/download-lead', {
          method:  'POST',
          headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': wpApiSettings.nonce },
          body:    JSON.stringify(enriched),
        })
          .then(function (response) {
            if (!response.ok) throw new Error('Server error');
            form.style.display        = 'none';
            successDiv.style.display  = 'block';
            if (typeof ym !== 'undefined') ym(108384915, 'reachGoal', 'exit_intent_lead');
          })
          .catch(function () {
            btn.disabled    = false;
            btn.textContent = 'Получить чек-лист';
          });
      });
    });

    var skip = el('button', 'p24-exit-popup__skip', 'Нет, спасибо');
    skip.addEventListener('click', close);

    box.appendChild(badge);
    box.appendChild(title);
    box.appendChild(text);
    box.appendChild(form);
    box.appendChild(successDiv);
    box.appendChild(skip);
  }

  function showPopup() {
    if (alreadyShown()) return;
    markShown();

    var variant = isProductOrSolutionPage() ? 'demo' : 'checklist';

    var popup   = el('div', 'p24-exit-popup');
    popup.setAttribute('role', 'dialog');
    popup.setAttribute('aria-modal', 'true');

    var overlay = el('div', 'p24-exit-popup__overlay');
    var box     = el('div', 'p24-exit-popup__box');
    var closeBtn = el('button', 'p24-exit-popup__close', '\u00D7');
    closeBtn.setAttribute('aria-label', 'Закрыть');

    function close() {
      popup.classList.remove('is-visible');
      setTimeout(function () { if (popup.parentNode) popup.parentNode.removeChild(popup); }, 300);
    }

    closeBtn.addEventListener('click', close);
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', function escHandler(e) {
      if (e.key === 'Escape') { close(); document.removeEventListener('keydown', escHandler); }
    });

    box.appendChild(closeBtn);

    if (variant === 'demo') {
      buildDemoVariant(box, close);
    } else {
      buildChecklistVariant(box, close);
    }

    popup.appendChild(overlay);
    popup.appendChild(box);
    document.body.appendChild(popup);

    requestAnimationFrame(function () {
      requestAnimationFrame(function () { popup.classList.add('is-visible'); });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    if (alreadyShown()) return;

    var delayPassed = false;

    // Enable triggers only after delay
    setTimeout(function () {
      delayPassed = true;
    }, DELAY_MS);

    // Trigger 1: exit intent (desktop — mouse leaves viewport from top, only after delay)
    document.addEventListener('mouseleave', function (e) {
      if (delayPassed && e.clientY <= 0) showPopup();
    });

    // Trigger 2: timed (shows after delay + 15s extra if user hasn't left)
    setTimeout(showPopup, DELAY_MS + 15000);
  });
})();
