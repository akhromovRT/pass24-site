/**
 * PASS24 Demo Form — многошаговая форма с auto-save и tracking
 *
 * @package PASS24_Child
 */

(function () {
	'use strict';

	var form = document.getElementById('p24-demo-form-el');
	if (!form) return;

	var formContainer = document.getElementById('p24-demo-form');
	var formSection = document.getElementById('demo-form-section');
	var thankyou = document.getElementById('p24-demo-thankyou');
	var progressFill = document.getElementById('p24-progress-fill');
	var stepEls = form.querySelectorAll('.p24-demo-step');
	var progressSteps = document.querySelectorAll('.p24-demo-progress__step');

	var currentStep = 1;
	var totalSteps = 3;
	var partialSaved = false;

	/* ---- Step navigation ---- */

	function showStep(n) {
		currentStep = n;

		stepEls.forEach(function (el) {
			el.classList.toggle('is-active', parseInt(el.dataset.step) === n);
		});

		progressSteps.forEach(function (el) {
			var s = parseInt(el.dataset.step);
			el.classList.toggle('is-active', s === n);
			el.classList.toggle('is-done', s < n);
		});

		progressFill.style.width = ((n / totalSteps) * 100) + '%';

		// Scroll form into view on mobile
		if (window.innerWidth < 768 && formContainer) {
			formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	}

	/* ---- Validation ---- */

	function validateStep(n) {
		var step = form.querySelector('.p24-demo-step[data-step="' + n + '"]');
		var fields = step.querySelectorAll('[required]');
		var valid = true;

		fields.forEach(function (field) {
			var wrapper = field.closest('.p24-demo-field');
			if (!wrapper) return;

			var errorEl = wrapper.querySelector('.p24-demo-field__error');

			// Radio group validation
			if (field.type === 'radio') {
				var radios = step.querySelectorAll('input[name="' + field.name + '"]');
				var checked = Array.prototype.some.call(radios, function (r) { return r.checked; });
				if (!checked) {
					wrapper.classList.add('has-error');
					if (errorEl) {
						errorEl.textContent = 'Выберите один из вариантов';
					}
					valid = false;
				} else {
					wrapper.classList.remove('has-error');
				}
				return;
			}

			// Standard field validation
			if (!field.value.trim()) {
				wrapper.classList.add('has-error');
				if (errorEl) {
					errorEl.textContent = 'Обязательное поле';
				}
				valid = false;
				return;
			}

			// Email validation
			if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) {
				wrapper.classList.add('has-error');
				if (errorEl) {
					errorEl.textContent = 'Введите корректный email';
				}
				valid = false;
				return;
			}

			// Phone validation (at least 10 digits)
			if (field.type === 'tel' && field.value.replace(/\D/g, '').length < 10) {
				wrapper.classList.add('has-error');
				if (errorEl) {
					errorEl.textContent = 'Введите корректный номер телефона';
				}
				valid = false;
				return;
			}

			wrapper.classList.remove('has-error');
		});

		return valid;
	}

	// Clear error on input
	form.addEventListener('input', function (e) {
		var wrapper = e.target.closest('.p24-demo-field');
		if (wrapper) {
			wrapper.classList.remove('has-error');
		}
	});

	form.addEventListener('change', function (e) {
		var wrapper = e.target.closest('.p24-demo-field');
		if (wrapper) {
			wrapper.classList.remove('has-error');
		}
	});

	/* ---- Next / Prev buttons ---- */

	form.addEventListener('click', function (e) {
		var nextBtn = e.target.closest('.p24-demo-next');
		var prevBtn = e.target.closest('.p24-demo-prev');

		if (nextBtn) {
			var nextStep = parseInt(nextBtn.dataset.next);
			if (validateStep(currentStep)) {
				showStep(nextStep);
				trackStep(nextStep);

				// Auto-save partial lead after Step 1
				if (currentStep === 2 && !partialSaved) {
					savePartialLead();
				}
			}
		}

		if (prevBtn) {
			showStep(parseInt(prevBtn.dataset.prev));
		}
	});

	/* ---- Form submission ---- */

	form.addEventListener('submit', function (e) {
		e.preventDefault();

		if (!validateStep(currentStep)) return;

		var submitBtn = form.querySelector('.p24-demo-submit');
		submitBtn.classList.add('is-loading');
		submitBtn.textContent = 'Отправка...';

		var data = collectFormData();

		// Enrich with UTM + Yandex ClientID, then send
		window.P24Analytics.enrichFormData(data, function (enriched) {
			fetch(getRestUrl('pass24/v1/demo-request'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': getNonce()
				},
				body: JSON.stringify(enriched)
			})
			.then(function () {
				showThankyou();
				trackConversion();
			})
			.catch(function () {
				showThankyou();
				trackConversion();
			});
		});
	});

	/* ---- Partial lead save (after Step 1) ---- */

	function savePartialLead() {
		partialSaved = true;
		var data = {
			name: form.querySelector('#p24-name').value,
			phone: form.querySelector('#p24-phone').value,
			email: form.querySelector('#p24-email').value,
			partial: true
		};

		window.P24Analytics.enrichFormData(data, function (enriched) {
			fetch(getRestUrl('pass24/v1/demo-request'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': getNonce()
				},
				body: JSON.stringify(enriched)
			}).catch(function () {});
		});
	}

	/* ---- Collect all form data ---- */

	function collectFormData() {
		var data = {};
		var formData = new FormData(form);
		formData.forEach(function (value, key) {
			if (key !== 'p24_demo_nonce' && key !== '_wp_http_referer') {
				data[key] = value;
			}
		});
		return data;
	}

	/* ---- Show thank you ---- */

	function showThankyou() {
		if (formSection) formSection.style.display = 'none';
		if (thankyou) {
			thankyou.style.display = 'block';
			thankyou.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	}

	/* ---- Analytics tracking ---- */

	function trackStep(step) {
		if (window.ym) {
			window.ym(108384915, 'reachGoal', 'demo_step_' + step);
		}
	}

	function trackConversion() {
		if (window.ym) {
			window.ym(108384915, 'reachGoal', 'demo_request');
			window.ym(108384915, 'reachGoal', 'generate_lead');
		}
	}

	/* ---- Utilities ---- */

	function getRestUrl(endpoint) {
		// WordPress REST API base URL
		if (window.wpApiSettings && window.wpApiSettings.root) {
			return window.wpApiSettings.root + endpoint;
		}
		return '/wp-json/' + endpoint;
	}

	function getNonce() {
		if (window.wpApiSettings && window.wpApiSettings.nonce) {
			return window.wpApiSettings.nonce;
		}
		var nonceField = form.querySelector('#p24_demo_nonce');
		return nonceField ? nonceField.value : '';
	}

})();
