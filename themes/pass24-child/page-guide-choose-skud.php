<?php
/**
 * Template: Guide — How to Choose Cloud SCUD (slug: guide-choose-skud)
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
          <div class="p24-guide-header__tag">Бесплатный гайд</div>
          <h1 class="p24-h1">Как выбрать облачную СКУД: 15 пунктов</h1>
          <p class="p24-subtitle">Практический чек-лист для руководителей ЖК, КП и бизнес-центров</p>
        </div>

        <div class="p24-guide-email-gate" id="guideEmailGate">
          <h2 class="p24-h3">Получите полный чек-лист бесплатно</h2>
          <p>Введите email — откроем доступ немедленно.</p>
          <form id="guideEmailForm">
            <label for="guideEmail" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">Email</label>
            <input type="email" class="p24-form-input" id="guideEmail" placeholder="Ваш email" required>
            <button type="submit" class="p24-btn p24-btn-primary">Открыть чек-лист</button>
          </form>
          <div id="guideEmailSuccess" style="display:none;margin-top:0.75rem;">
            <p style="color:var(--p24-accent);font-weight:600;">Доступ открыт! Прокрутите вниз.</p>
          </div>
        </div>

        <div class="p24-guide-content" id="guideContent">
          <ol class="p24-guide-checklist">
            <li><strong>Облако или on-premise?</strong> Облачная система не требует серверов, обновляется автоматически и работает с любого устройства. On-premise — для объектов с жёсткими требованиями к изоляции данных.</li>
            <li><strong>Мобильное приложение для жителей</strong> — обязательный критерий. Проверьте: iOS + Android, офлайн-режим, push-уведомления.</li>
            <li><strong>Интеграция с видеонаблюдением</strong> — просмотр камер прямо в приложении СКУД. Уточните поддерживаемые бренды.</li>
            <li><strong>Гостевые пропуска из веба</strong> — жильцы должны выдавать разовые пропуска без звонка администратору.</li>
            <li><strong>Журнал событий в реальном времени</strong> — кто, когда, через какую точку прошёл. Экспорт в Excel.</li>
            <li><strong>Чёрные списки и ограничения</strong> — блокировка без перезагрузки системы.</li>
            <li><strong>Масштабируемость</strong> — от 1 шлагбаума до 100+ точек без замены оборудования.</li>
            <li><strong>Безлимитные пользователи</strong> — тариф не растёт с ростом числа жителей.</li>
            <li><strong>API и вебхуки</strong> — интеграция с Bitrix24, amoCRM, 1С. Без этого автоматизация невозможна.</li>
            <li><strong>SLA и техподдержка</strong> — реакция в рабочее время до 4 часов, 24/7 для критичных инцидентов.</li>
            <li><strong>Распознавание номеров (LPR)</strong> — если есть автомобильный въезд. Проверьте точность ночью.</li>
            <li><strong>Цифровой ключ (BLE/NFC)</strong> — открытие смартфоном без интернета.</li>
            <li><strong>Контроль времени на территории</strong> — учёт рабочего времени, ограничение по расписанию.</li>
            <li><strong>Обучение и внедрение</strong> — наличие онбординга, видеоинструкций, персонального менеджера.</li>
            <li><strong>Пилот перед контрактом</strong> — поставщик должен дать бесплатный тест на 14–30 дней. Отказ — красный флаг.</li>
          </ol>

          <div style="margin-top:3rem;padding:2rem;background:color-mix(in srgb, var(--p24-primary) 8%, white);border-radius:var(--p24-radius-xl);text-align:center;">
            <h3 class="p24-h3">PASS24 соответствует всем 15 пунктам</h3>
            <p>Убедитесь сами — запишитесь на бесплатную демонстрацию.</p>
            <a href="/demo/" class="p24-btn p24-btn-primary">Записаться на демо</a>
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

  // Unlock if already submitted this session
  try { if (sessionStorage.getItem('p24_guide_choose_ok')) { gate.style.display = 'none'; content.classList.add('is-unlocked'); } } catch (e) {}

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var emailInput = document.getElementById('guideEmail');
    var email = emailInput.value.trim();
    if (!email) return;
    var btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Открываем...';

    var data = { email: email, source: 'guide_choose_skud' };
    window.P24Analytics.enrichFormData(data, function (enriched) {
      fetch(window.wpApiSettings.root + 'pass24/v1/download-lead', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': window.wpApiSettings.nonce },
        body:    JSON.stringify(enriched),
      })
        .then(function (response) {
          if (!response.ok) throw new Error('Server error');
          form.style.display = 'none';
          success.style.display = 'block';
          content.classList.add('is-unlocked');
          try { sessionStorage.setItem('p24_guide_choose_ok', '1'); } catch (er) {}
          if (typeof ym !== 'undefined') ym(108384915, 'reachGoal', 'guide_download');
          setTimeout(function () { gate.style.display = 'none'; }, 2500);
        })
        .catch(function () { btn.disabled = false; btn.textContent = 'Открыть чек-лист'; });
    });
  });
})();
</script>
<?php get_footer(); ?>
