/**
 * Exit-intent popup — PASS24
 * Triggers:
 *   1. mouseout to top of page (desktop) on /products/* and /solutions/*
 *   2. After 30 seconds on all pages
 * Shows once per session (sessionStorage).
 */
(function () {
  'use strict';

  var SESSION_KEY = 'p24_exit_shown';
  var DELAY_MS    = 30000;

  function alreadyShown() {
    try { return sessionStorage.getItem(SESSION_KEY) === '1'; } catch (e) { return false; }
  }

  function markShown() {
    try { sessionStorage.setItem(SESSION_KEY, '1'); } catch (e) {}
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
    form.appendChild(input);
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

      fetch(wpApiSettings.root + 'pass24/v1/download-lead', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': wpApiSettings.nonce },
        body:    JSON.stringify({ email: email, source: 'exit_intent_checklist' }),
      })
        .then(function (response) {
          if (!response.ok) throw new Error('Server error');
          form.style.display        = 'none';
          successDiv.style.display  = 'block';
          if (typeof ym !== 'undefined')   ym(108384915, 'reachGoal', 'exit_intent_lead');
          if (typeof gtag !== 'undefined') gtag('event', 'generate_lead', { event_category: 'exit_intent' });
        })
        .catch(function () {
          btn.disabled    = false;
          btn.textContent = 'Получить чек-лист';
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

    // Trigger 1: exit intent (desktop — mouse leaves viewport from top)
    document.addEventListener('mouseleave', function (e) {
      if (e.clientY <= 0) showPopup();
    });

    // Trigger 2: timed
    setTimeout(showPopup, DELAY_MS);
  });
})();
