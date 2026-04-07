/**
 * ROI Calculator — логика расчёта и email-gate
 *
 * IIFE + 'use strict'. Только textContent для DOM-обновлений.
 * Зависит от wpApiSettings (инжектируется через wp_localize_script).
 *
 * @package PASS24_Child
 */

(function () {
  'use strict';

  /* ------------------------------------------------------------------
     Константы тарифов и расчётов
     ------------------------------------------------------------------ */

  var PLANS = {
    LITE:     { name: 'LITE',     price: 10990 },
    STANDARD: { name: 'STANDARD', price: 14990 },
    PROF:     { name: 'PROF',     price: 24990 },
    BUSINESS: { name: 'BUSINESS', price: 48990 },
  };

  /** Стоимость часа администратора, руб */
  var ADMIN_RATE = 2000;

  /* ------------------------------------------------------------------
     Выбор рекомендуемого тарифа
     ------------------------------------------------------------------ */

  function getPlan(objectType, points) {
    var pts = parseInt(points, 10);

    if (objectType === 'uk') {
      return PLANS.BUSINESS;
    }

    if (pts >= 50) {
      return PLANS.PROF;
    }

    if (pts >= 20) {
      return PLANS.STANDARD;
    }

    // zhk/kp/bc/office с малым числом точек
    if (objectType === 'zhk' || objectType === 'kp') {
      return PLANS.STANDARD;
    }

    return PLANS.LITE;
  }

  /* ------------------------------------------------------------------
     Форматирование числа: 1234567 → "1 234 567 ₽"
     ------------------------------------------------------------------ */

  function formatRub(value) {
    var abs = Math.abs(Math.round(value));
    var str = abs.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '\u00a0');
    return (value < 0 ? '-' : '') + str + '\u00a0\u20bd';
  }

  function formatHours(value) {
    return Math.round(value) + '\u00a0ч/мес';
  }

  /* ------------------------------------------------------------------
     Состояние текущего расчёта (для передачи в форму)
     ------------------------------------------------------------------ */

  var state = {
    objectType:  '',
    points:      0,
    currentCost: 0,
    adminHours:  0,
    plan:        null,
    monthlySavings: 0,
  };

  /* ------------------------------------------------------------------
     Расчёт и отрисовка результатов
     ------------------------------------------------------------------ */

  function calculate() {
    var objectType  = document.getElementById('roiObjectType').value;
    var points      = document.getElementById('roiPoints').value;
    var currentCost = parseInt(document.getElementById('roiCurrentCost').value, 10) || 0;
    var adminHours  = parseInt(document.getElementById('roiAdminHours').value, 10) || 0;

    var plan = getPlan(objectType, points);

    var adminSaved      = Math.round(adminHours * 0.7);
    var adminSavingsRub = adminHours * 0.7 * ADMIN_RATE;
    var monthlySavings  = (currentCost - plan.price) + adminSavingsRub;
    var yearlySavings   = monthlySavings * 12;
    var tco36Current    = currentCost * 36;
    var tco36Pass24     = plan.price * 36;

    // Обновляем состояние для формы
    state.objectType     = objectType;
    state.points         = points;
    state.currentCost    = currentCost;
    state.adminHours     = adminHours;
    state.plan           = plan;
    state.monthlySavings = monthlySavings;

    // Обновляем DOM — только textContent
    document.getElementById('roiPlanBadge').textContent      = plan.name + ' — ' + formatRub(plan.price) + '/мес';
    document.getElementById('roiMonthlySavings').textContent  = formatRub(monthlySavings);
    document.getElementById('roiYearlySavings').textContent   = formatRub(yearlySavings);
    document.getElementById('roiAdminHoursSaved').textContent = formatHours(adminSaved);
    document.getElementById('roiTcoCurrent').textContent      = formatRub(tco36Current);
    document.getElementById('roiTcoPass24').textContent       = formatRub(tco36Pass24);

    // Показываем результаты
    document.getElementById('roiInputs').style.display  = 'none';
    document.getElementById('roiResults').style.display = '';

    // Сбрасываем форму/успех в исходное состояние при каждом пересчёте
    document.getElementById('roiEmailForm').style.display    = '';
    document.getElementById('roiEmailSuccess').style.display = 'none';
  }

  /* ------------------------------------------------------------------
     Пересчёт — возврат к форме ввода
     ------------------------------------------------------------------ */

  function recalculate() {
    document.getElementById('roiInputs').style.display  = '';
    document.getElementById('roiResults').style.display = 'none';
  }

  /* ------------------------------------------------------------------
     Отправка lead-формы
     ------------------------------------------------------------------ */

  function submitLead() {
    var name  = document.getElementById('roiName').value.trim();
    var email = document.getElementById('roiEmail').value.trim();
    var phone = document.getElementById('roiPhone').value.trim();
    var btn   = document.getElementById('roiSubmitBtn');

    if (!email && !phone) {
      document.getElementById('roiEmail').focus();
      return;
    }

    btn.disabled = true;
    btn.textContent = 'Отправляем…';

    var apiRoot = (window.wpApiSettings && window.wpApiSettings.root) ? window.wpApiSettings.root : '/wp-json/';
    var nonce   = (window.wpApiSettings && window.wpApiSettings.nonce) ? window.wpApiSettings.nonce : '';

    var payload = {
      name:              name,
      email:             email,
      phone:             phone,
      object_type:       state.objectType,
      recommended_plan:  state.plan ? state.plan.name : '',
      monthly_savings:   state.monthlySavings,
    };

    window.P24Analytics.enrichFormData(payload, function (enriched) {
      fetch(apiRoot + 'pass24/v1/roi-lead', {
        method:  'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce':   nonce,
        },
        body: JSON.stringify(enriched),
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data && data.success) {
            document.getElementById('roiEmailForm').style.display    = 'none';
            document.getElementById('roiEmailSuccess').style.display = '';

            if (typeof window.ym === 'function') {
              window.ym(108384915, 'reachGoal', 'roi_calculator_lead');
            }
          } else {
            btn.disabled = false;
          btn.textContent = 'Получить предложение';
        }
      })
      .catch(function () {
        btn.disabled = false;
        btn.textContent = 'Получить предложение';
      });
    });
  }

  /* ------------------------------------------------------------------
     Инициализация после загрузки DOM
     ------------------------------------------------------------------ */

  function init() {
    var calcBtn    = document.getElementById('roiCalcBtn');
    var recalcBtn  = document.getElementById('roiRecalcBtn');
    var submitBtn  = document.getElementById('roiSubmitBtn');

    if (!calcBtn || !recalcBtn || !submitBtn) {
      return;
    }

    calcBtn.addEventListener('click', calculate);
    recalcBtn.addEventListener('click', recalculate);
    submitBtn.addEventListener('click', submitLead);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

}());
