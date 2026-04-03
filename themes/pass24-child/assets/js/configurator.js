/**
 * PASS24 Solution Configurator — 4-step wizard
 *
 * @package PASS24_Child
 */

( function () {
	'use strict';

	/* ------------------------------------------------------------------
	   Данные тарифов
	   ------------------------------------------------------------------ */

	var PLANS = {
		LITE: {
			name: 'LITE',
			price: 10990,
			features: [
				'Мобильное приложение',
				'Электронный журнал',
				'Безлимитные пользователи',
				'Отчёты и аналитика',
			],
		},
		STANDARD: {
			name: 'STANDARD',
			price: 14990,
			features: [
				'Всё из LITE',
				'Несколько адресных групп',
				'Гостевые пропуска из веба',
			],
		},
		PROF: {
			name: 'PROF',
			price: 24990,
			features: [
				'Всё из STANDARD',
				'Распознавание номеров (LPR)',
				'Интеграция со СКУД',
				'Расширенные уведомления',
			],
		},
		BUSINESS: {
			name: 'BUSINESS',
			price: 48990,
			features: [
				'Всё из PROF',
				'Мульти-объектность',
				'Разграничение прав',
				'Персональный менеджер',
			],
		},
	};

	/* ------------------------------------------------------------------
	   Состояние визарда
	   ------------------------------------------------------------------ */

	var state = {
		objectType:   null,
		accessPoints: null,
		methods:      [],
		integrations: [],
	};

	/* ------------------------------------------------------------------
	   Вспомогательные функции
	   ------------------------------------------------------------------ */

	function fmtRub( amount ) {
		return amount.toLocaleString( 'ru-RU' ) + ' руб/мес';
	}

	function getRecommendedKey( s ) {
		if ( s.objectType === 'uk' ) {
			return 'BUSINESS';
		}
		if (
			s.methods.indexOf( 'lpr' ) >= 0 ||
			s.integrations.indexOf( 'skud' ) >= 0 ||
			parseInt( s.accessPoints, 10 ) >= 50
		) {
			return 'PROF';
		}
		if ( [ 'zhk', 'kp', 'bc', 'office' ].indexOf( s.objectType ) >= 0 ) {
			return 'STANDARD';
		}
		return 'LITE';
	}

	/* ------------------------------------------------------------------
	   Отображение шага
	   ------------------------------------------------------------------ */

	function showStep( n ) {
		var steps = document.querySelectorAll( '.p24-cfg-step' );
		for ( var i = 0; i < steps.length; i++ ) {
			steps[ i ].hidden = true;
		}

		var target = document.getElementById( 'cfgStep' + n );
		if ( target ) {
			target.hidden = false;
		}

		var fill  = document.getElementById( 'cfgProgressFill' );
		var label = document.getElementById( 'cfgProgressLabel' );
		if ( fill ) {
			fill.style.width = ( n * 25 ) + '%';
		}
		if ( label ) {
			label.textContent = 'Шаг ' + n + ' из 4';
		}

		var progress = fill && fill.parentElement;
		if ( progress ) {
			progress.setAttribute( 'aria-valuenow', n * 25 );
		}
	}

	/* ------------------------------------------------------------------
	   Показ результатов
	   ------------------------------------------------------------------ */

	function showResults() {
		var key  = getRecommendedKey( state );
		var plan = PLANS[ key ];

		var planName  = document.getElementById( 'cfgPlanName' );
		var planPrice = document.getElementById( 'cfgPlanPrice' );
		var featureList = document.getElementById( 'cfgResultFeatures' );

		if ( planName ) {
			planName.textContent = plan.name;
		}
		if ( planPrice ) {
			planPrice.textContent = fmtRub( plan.price );
		}

		if ( featureList ) {
			// Очистить список без innerHTML
			while ( featureList.firstChild ) {
				featureList.removeChild( featureList.firstChild );
			}
			for ( var i = 0; i < plan.features.length; i++ ) {
				var li   = document.createElement( 'li' );
				li.textContent = plan.features[ i ];
				featureList.appendChild( li );
			}
		}

		// Сохранить ключ плана для отправки
		state._recommendedKey = key;

		// Показать блок результатов
		var steps = document.querySelectorAll( '.p24-cfg-step' );
		for ( var j = 0; j < steps.length; j++ ) {
			steps[ j ].hidden = true;
		}
		var results = document.getElementById( 'cfgResults' );
		if ( results ) {
			results.hidden = false;
		}

		var fill  = document.getElementById( 'cfgProgressFill' );
		var label = document.getElementById( 'cfgProgressLabel' );
		if ( fill ) {
			fill.style.width = '100%';
		}
		if ( label ) {
			label.textContent = 'Готово!';
		}
	}

	/* ------------------------------------------------------------------
	   Отправка лида
	   ------------------------------------------------------------------ */

	function submitLead() {
		var nameEl  = document.getElementById( 'cfgName' );
		var phoneEl = document.getElementById( 'cfgPhone' );
		var emailEl = document.getElementById( 'cfgEmail' );
		var btn     = document.getElementById( 'cfgSubmitBtn' );

		var phone = phoneEl ? phoneEl.value.trim() : '';

		if ( ! phone ) {
			if ( phoneEl ) {
				phoneEl.focus();
			}
			return;
		}

		if ( btn ) {
			btn.disabled = true;
			btn.textContent = 'Отправка…';
		}

		var payload = {
			name:             nameEl  ? nameEl.value.trim()  : '',
			phone:            phone,
			email:            emailEl ? emailEl.value.trim() : '',
			object_type:      state.objectType      || '',
			recommended_plan: state._recommendedKey || '',
		};

		var root  = ( window.wpApiSettings && window.wpApiSettings.root )  || '/wp-json/';
		var nonce = ( window.wpApiSettings && window.wpApiSettings.nonce ) || '';

		fetch( root + 'pass24/v1/configurator-lead', {
			method:  'POST',
			headers: {
				'Content-Type':   'application/json',
				'X-WP-Nonce':     nonce,
			},
			body: JSON.stringify( payload ),
		} )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( data ) {
				if ( data && data.success ) {
					var form    = document.getElementById( 'cfgContactForm' );
					var success = document.getElementById( 'cfgContactSuccess' );
					if ( form )    { form.hidden    = true; }
					if ( success ) { success.hidden = false; }

					// GA4
					if ( window.gtag ) {
						window.gtag( 'event', 'configurator_lead', {
							event_category: 'lead',
							recommended_plan: payload.recommended_plan,
						} );
					}

					// Яндекс.Метрика
					if ( window.ym ) {
						window.ym( 108384915, 'reachGoal', 'configurator_lead' );
					}
				}
			} )
			.catch( function () {
				if ( btn ) {
					btn.disabled    = false;
					btn.textContent = 'Получить предложение';
				}
			} );
	}

	/* ------------------------------------------------------------------
	   Сброс визарда
	   ------------------------------------------------------------------ */

	function resetWizard() {
		state.objectType      = null;
		state.accessPoints    = null;
		state.methods         = [];
		state.integrations    = [];
		state._recommendedKey = null;

		// Снять все отметки
		var inputs = document.querySelectorAll( '.p24-cfg-options input' );
		for ( var i = 0; i < inputs.length; i++ ) {
			inputs[ i ].checked = false;
		}

		// Задизейблить все кнопки "Далее" (radio-шаги)
		var nextBtns = document.querySelectorAll( '.p24-cfg-next[data-next="2"], .p24-cfg-next[data-next="3"]' );
		for ( var j = 0; j < nextBtns.length; j++ ) {
			nextBtns[ j ].disabled = true;
		}

		// Скрыть результаты, показать форму
		var results = document.getElementById( 'cfgResults' );
		if ( results ) { results.hidden = true; }

		var form    = document.getElementById( 'cfgContactForm' );
		var success = document.getElementById( 'cfgContactSuccess' );
		if ( form )    { form.hidden    = false; }
		if ( success ) { success.hidden = true;  }

		var btn = document.getElementById( 'cfgSubmitBtn' );
		if ( btn ) {
			btn.disabled    = false;
			btn.textContent = 'Получить предложение';
		}

		// Очистить поля формы
		var nameEl  = document.getElementById( 'cfgName' );
		var phoneEl = document.getElementById( 'cfgPhone' );
		var emailEl = document.getElementById( 'cfgEmail' );
		if ( nameEl )  { nameEl.value  = ''; }
		if ( phoneEl ) { phoneEl.value = ''; }
		if ( emailEl ) { emailEl.value = ''; }

		showStep( 1 );
	}

	/* ------------------------------------------------------------------
	   Инициализация
	   ------------------------------------------------------------------ */

	function init() {
		// Клики по radio-вариантам (шаги 1 и 2)
		var radioGroups = document.querySelectorAll(
			'[data-field="objectType"], [data-field="accessPoints"]'
		);
		for ( var g = 0; g < radioGroups.length; g++ ) {
			( function ( group ) {
				var field   = group.getAttribute( 'data-field' );
				var radios  = group.querySelectorAll( 'input[type="radio"]' );

				for ( var r = 0; r < radios.length; r++ ) {
					radios[ r ].addEventListener( 'change', function ( e ) {
						state[ field ] = e.target.value;

						// Найти кнопку "Далее" в родительском шаге
						var stepEl  = group.closest( '.p24-cfg-step' );
						if ( stepEl ) {
							var nextBtn = stepEl.querySelector( '.p24-cfg-next' );
							if ( nextBtn ) {
								nextBtn.disabled = false;
							}
						}
					} );
				}
			} )( radioGroups[ g ] );
		}

		// Клики по checkbox-вариантам (шаги 3 и 4)
		var checkGroups = document.querySelectorAll(
			'[data-field="methods"], [data-field="integrations"]'
		);
		for ( var c = 0; c < checkGroups.length; c++ ) {
			( function ( group ) {
				var field     = group.getAttribute( 'data-field' );
				var checkboxes = group.querySelectorAll( 'input[type="checkbox"]' );

				for ( var cb = 0; cb < checkboxes.length; cb++ ) {
					checkboxes[ cb ].addEventListener( 'change', function () {
						var checked = group.querySelectorAll( 'input[type="checkbox"]:checked' );
						var values  = [];
						for ( var v = 0; v < checked.length; v++ ) {
							values.push( checked[ v ].value );
						}
						state[ field ] = values;
					} );
				}
			} )( checkGroups[ c ] );
		}

		// Кнопки "Далее"
		var nextBtns = document.querySelectorAll( '.p24-cfg-next' );
		for ( var nb = 0; nb < nextBtns.length; nb++ ) {
			nextBtns[ nb ].addEventListener( 'click', function ( e ) {
				var n = parseInt( e.currentTarget.getAttribute( 'data-next' ), 10 );
				if ( n ) {
					showStep( n );
				}
			} );
		}

		// Кнопки "Назад"
		var backBtns = document.querySelectorAll( '.p24-cfg-back' );
		for ( var bb = 0; bb < backBtns.length; bb++ ) {
			backBtns[ bb ].addEventListener( 'click', function ( e ) {
				var n = parseInt( e.currentTarget.getAttribute( 'data-back' ), 10 );
				if ( n ) {
					showStep( n );
				}
			} );
		}

		// Показать результаты (шаг 4)
		var showResultsBtn = document.getElementById( 'cfgShowResults' );
		if ( showResultsBtn ) {
			showResultsBtn.addEventListener( 'click', showResults );
		}

		// Отправить лид
		var submitBtn = document.getElementById( 'cfgSubmitBtn' );
		if ( submitBtn ) {
			submitBtn.addEventListener( 'click', submitLead );
		}

		// Сброс
		var restartBtn = document.getElementById( 'cfgRestartBtn' );
		if ( restartBtn ) {
			restartBtn.addEventListener( 'click', resetWizard );
		}
	}

	// Запуск после загрузки DOM
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}

} )();
