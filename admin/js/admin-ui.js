/**
 * UI moderna do painel admin: confirms, loading e toasts.
 * Mantém as APIs legadas conf() / wait() / loadShow() / loadHide().
 */
(function (window, $) {
	'use strict';

	function ensureBootbox() {
		if (typeof bootbox === 'undefined') {
			return false;
		}
		try {
			if (!bootbox.locales || !bootbox.locales.pt) {
				bootbox.addLocale('pt', {
					OK: 'OK',
					CANCEL: 'Cancelar',
					CONFIRM: 'Confirmar'
				});
			}
			bootbox.setLocale('pt');
		} catch (e) { /* ignore */ }
		return true;
	}

	function adminToast(opts) {
		opts = opts || {};
		var heading = opts.heading || '';
		var text = opts.text || '';
		var icon = opts.icon || 'info';
		var hideAfter = typeof opts.hideAfter === 'number' ? opts.hideAfter : 4200;

		if ($ && $.toast) {
			$.toast({
				heading: heading,
				text: text,
				icon: icon,
				position: 'top-right',
				loader: true,
				loaderBg: icon === 'error' ? '#ea0606' : (icon === 'success' ? '#82d616' : '#2152ff'),
				bgColor: icon === 'error' ? '#ea0606' : (icon === 'success' ? '#17c1e8' : '#344767'),
				textColor: '#fff',
				hideAfter: hideAfter,
				stack: 5
			});
			return;
		}

		if (ensureBootbox()) {
			bootbox.alert({
				title: heading || 'Aviso',
				message: text,
				centerVertical: true,
				backdrop: true
			});
			return;
		}

		window.alert((heading ? heading + ': ' : '') + text);
	}

	function escapeHtml(text) {
		return String(text == null ? '' : text)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}

	function adminConfirm(message, options) {
		options = options || {};
		return new Promise(function (resolve, reject) {
			if (!ensureBootbox()) {
				if (window.confirm(message)) {
					resolve(true);
				} else {
					reject(false);
				}
				return;
			}

			var danger = !!options.danger;
			bootbox.confirm({
				title: options.title || (danger ? 'Confirmar exclusão' : 'Confirmação'),
				message: '<div class="admin-confirm-msg">' + escapeHtml(message) + '</div>',
				centerVertical: true,
				swapButtonOrder: true,
				backdrop: true,
				onEscape: true,
				buttons: {
					cancel: {
						label: options.cancelLabel || 'Cancelar',
						className: 'btn btn-sm btn-outline-secondary mb-0'
					},
					confirm: {
						label: options.confirmLabel || (danger ? 'Excluir' : 'Confirmar'),
						className: danger
							? 'btn btn-sm bg-gradient-danger mb-0'
							: 'btn btn-sm bg-gradient-info mb-0'
					}
				},
				callback: function (result) {
					if (result) {
						resolve(true);
					} else {
						reject(false);
					}
				}
			});
		});
	}

	function setWaiting(on) {
		var el = document.getElementById('waitLoad');
		if (!el) {
			return;
		}
		if (on) {
			el.classList.add('is-active');
			el.setAttribute('aria-hidden', 'false');
		} else {
			el.classList.remove('is-active');
			el.setAttribute('aria-hidden', 'true');
		}
	}

	window.AdminUI = {
		toast: adminToast,
		confirm: adminConfirm,
		wait: function () { setWaiting(true); },
		waitHide: function () { setWaiting(false); },
		flashSuccess: function (text) {
			adminToast({ heading: 'Sucesso', text: text, icon: 'success' });
		},
		flashError: function (text) {
			adminToast({ heading: 'Atenção', text: text, icon: 'error' });
		}
	};

	window.conf = function (pergunta, fn) {
		var danger = /deletar|excluir|remover|apagar/i.test(String(pergunta || ''));
		adminConfirm(pergunta, { danger: danger }).then(function () {
			if (typeof fn === 'function') {
				fn();
			}
		}).catch(function () { /* cancelado */ });
		return false;
	};

	window.wait = function (aux) {
		if (arguments.length === 0) {
			setWaiting(true);
			return;
		}
		if (aux === 'on' || aux === true) {
			setWaiting(true);
		} else {
			setWaiting(false);
		}
	};

	window.loadShow = function () {
		setWaiting(true);
	};

	window.loadHide = function () {
		setWaiting(false);
	};

	$(function () {
		ensureBootbox();

		var ok = document.getElementById('admin-flash-ok');
		if (ok && ok.getAttribute('data-message')) {
			AdminUI.flashSuccess(ok.getAttribute('data-message'));
		}
		var no = document.getElementById('admin-flash-no');
		if (no && no.getAttribute('data-message')) {
			AdminUI.flashError(no.getAttribute('data-message'));
		}
	});
})(window, window.jQuery);
