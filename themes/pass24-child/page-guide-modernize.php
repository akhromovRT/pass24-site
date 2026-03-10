<?php
/**
 * Template: Guide — Modernization for UK (slug: guide-modernize)
 * @package PASS24_Child
 */
defined( 'ABSPATH' ) || exit;
add_filter( 'generate_show_sidebar', '__return_false', 99 );
get_header();
?>
<div class="site-content" id="content">
  <div class="content-area" style="width:100%;">
    <main class="site-main">
      <div class="p24-guide-wrapper">

        <div class="p24-guide-header">
          <div class="p24-guide-header__logo">PASS24.online</div>
          <div class="p24-guide-header__tag">Гайд для УК</div>
          <h1 class="p24-h1">Модернизация СКУД для управляющей компании</h1>
          <p class="p24-subtitle">Пошаговый план перехода на облачное управление доступом без остановки объектов</p>
        </div>

        <div class="p24-guide-email-gate" id="guideEmailGate">
          <h2 class="p24-h3">Получите гайд бесплатно</h2>
          <p>Введите email — план модернизации откроется сразу.</p>
          <form id="guideEmailForm">
            <input type="email" class="p24-form-input" id="guideEmail" placeholder="Ваш email" required>
            <button type="submit" class="p24-btn p24-btn-primary">Открыть гайд</button>
          </form>
          <div id="guideEmailSuccess" style="display:none;margin-top:0.75rem;">
            <p style="color:var(--p24-accent);font-weight:600;">Доступ открыт! Прокрутите вниз.</p>
          </div>
        </div>

        <div class="p24-guide-content" id="guideContent">
          <h2 class="p24-h2">Этап 1 — Аудит текущей системы (1–2 недели)</h2>
          <ol class="p24-guide-checklist">
            <li><strong>Инвентаризация точек доступа</strong> — составьте реестр всех шлагбаумов, домофонов, калиток. Укажите тип оборудования и год установки.</li>
            <li><strong>Анализ текущих затрат</strong> — посчитайте ежемесячные расходы: обслуживание, ремонты, зарплата операторов, стоимость карт.</li>
            <li><strong>Оценка удовлетворённости жителей</strong> — опрос по 3 вопросам: удобство, скорость, надёжность.</li>
          </ol>

          <h2 class="p24-h2" style="margin-top:2rem;">Этап 2 — Выбор и пилот (2–4 недели)</h2>
          <ol class="p24-guide-checklist">
            <li><strong>Пилот на одном объекте</strong> — выберите объект с 1–2 точками доступа. Запустите параллельно с текущей системой.</li>
            <li><strong>Обучение администраторов</strong> — 2–3 часа на веб-интерфейс, 1 час на мобильное приложение.</li>
            <li><strong>Миграция данных</strong> — перенос базы жителей и автомобилей. Запросите шаблон импорта у поставщика.</li>
          </ol>

          <h2 class="p24-h2" style="margin-top:2rem;">Этап 3 — Масштабирование (1–3 месяца)</h2>
          <ol class="p24-guide-checklist">
            <li><strong>Поэтапный rollout</strong> — подключайте 2–4 объекта в месяц. Не все сразу.</li>
            <li><strong>Коммуникация с жителями</strong> — за 2 недели рассылайте инструкции: PDF + QR на скачивание приложения.</li>
            <li><strong>Мониторинг метрик</strong> — кол-во обращений в поддержку, время открытия ворот, процент успешных проездов.</li>
          </ol>

          <h2 class="p24-h2" style="margin-top:2rem;">Типичные ошибки</h2>
          <ul style="padding-left:1.5rem;line-height:1.9;">
            <li>Переключать все объекты одновременно — высокий риск сбоя</li>
            <li>Не информировать жителей заранее — волна жалоб в первые дни</li>
            <li>Не тестировать офлайн-режим — что происходит при отключении интернета?</li>
            <li>Забыть про сервисный въезд — отдельная логика для клининга, поставщиков</li>
          </ul>

          <div style="margin-top:3rem;padding:2rem;background:color-mix(in srgb, var(--p24-primary) 8%, white);border-radius:var(--p24-radius-xl);text-align:center;">
            <h3 class="p24-h3">PASS24 внедряется за 14 дней</h3>
            <p>Персональный менеджер проведёт через все этапы бесплатно.</p>
            <a href="/demo/" class="p24-btn p24-btn-primary">Обсудить план модернизации</a>
          </div>
        </div>

      </div>
    </main>
  </div>
</div>

<script>
(function () {
  var form    = document.getElementById('guideEmailForm');
  var gate    = document.getElementById('guideEmailGate');
  var content = document.getElementById('guideContent');
  var success = document.getElementById('guideEmailSuccess');
  if (!form) return;

  try { if (sessionStorage.getItem('p24_guide_modernize_ok')) { gate.style.display = 'none'; content.classList.add('is-unlocked'); } } catch (e) {}

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var emailInput = document.getElementById('guideEmail');
    var email = emailInput.value.trim();
    if (!email) return;
    var btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Открываем...';

    fetch(window.wpApiSettings.root + 'pass24/v1/download-lead', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': window.wpApiSettings.nonce },
      body:    JSON.stringify({ email: email, source: 'guide_modernize' }),
    })
      .then(function (response) {
        if (!response.ok) throw new Error('Server error');
        form.style.display = 'none';
        success.style.display = 'block';
        content.classList.add('is-unlocked');
        try { sessionStorage.setItem('p24_guide_modernize_ok', '1'); } catch (er) {}
        if (typeof ym !== 'undefined') ym(106989472, 'reachGoal', 'guide_download');
        setTimeout(function () { gate.style.display = 'none'; }, 2500);
      })
      .catch(function () { btn.disabled = false; btn.textContent = 'Открыть гайд'; });
  });
})();
</script>
<?php get_footer(); ?>
