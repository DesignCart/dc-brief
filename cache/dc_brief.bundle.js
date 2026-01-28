/* DC BRIEF BUNDLE - generated: 2026-01-28T12:12:37+00:00 */

/* ===== dc_brief.js (mtime: 2026-01-26T10:33:02+00:00) ===== */
function dc_validateBrief(root) {
	if (!root) return true;

	const form = root.querySelector('.dc-brief-form');
	if (!form) return true;

	let isValid = true;

	const errorText = root.getAttribute('data-input-error') || 'To pole jest wymagane';

	// wyczyść stare błędy
	form.querySelectorAll('.dc-brief-form__field').forEach(field => {
		field.classList.remove('dc-brief-form__field--error');
		const oldMsg = field.querySelector('.dc-brief-form__error');
		if (oldMsg) oldMsg.remove();
	});

	// helper: pokaż błąd
	function showError(fieldWrap) {
		if (!fieldWrap) return;

		fieldWrap.classList.add('dc-brief-form__field--error');

		if (!fieldWrap.querySelector('.dc-brief-form__error')) {
			const div = document.createElement('div');
			div.className = 'dc-brief-form__error';
			div.textContent = errorText;
			fieldWrap.appendChild(div);
		}
	}

	form.querySelectorAll(
		'input[required]:not([type=radio]):not([type=checkbox]), textarea[required], select[required]'
	).forEach(input => {
		const fieldWrap = input.closest('.dc-brief-form__field');
		if (!input.value || input.value.trim() === '') {
			isValid = false;
			showError(fieldWrap);
		}
	});

	form.querySelectorAll('.dc-brief-form__checkbox-one input[type=checkbox][required]').forEach(chk => {
		const fieldWrap = chk.closest('.dc-brief-form__field');
		if (!chk.checked) {
			isValid = false;
			showError(fieldWrap);
		}
	});

	const radioNames = new Set();
	form.querySelectorAll('input[type=radio][required]').forEach(r => radioNames.add(r.name));

	radioNames.forEach(name => {
		const radios = form.querySelectorAll('input[type=radio][name="' + CSS.escape(name) + '"]');
		const checked = Array.from(radios).some(r => r.checked);
		if (!checked) {
			isValid = false;
			const fieldWrap = radios[0].closest('.dc-brief-form__field');
			showError(fieldWrap);
		}
	});

	const checkboxNames = new Set();
	form.querySelectorAll('.dc-brief-form__options--checkboxes input[type=checkbox][required]').forEach(c => {
		checkboxNames.add(c.name);
	});

	checkboxNames.forEach(name => {
		const boxes = form.querySelectorAll('input[type=checkbox][name="' + CSS.escape(name) + '"]');
		const checked = Array.from(boxes).some(c => c.checked);
		if (!checked) {
			isValid = false;
			const fieldWrap = boxes[0].closest('.dc-brief-form__field');
			showError(fieldWrap);
		}
	});

	return isValid;
}

function dc_getBrief(root) {
	if (!root) return [];

	const form = root.querySelector('.dc-brief-form');
	if (!form) return [];

	const result = [];

	// każda grupa
	form.querySelectorAll('.dc-brief-form__group').forEach(groupEl => {
		const groupTitleEl = groupEl.querySelector('.dc-brief-form__group-title');
		const groupTitle = groupTitleEl ? groupTitleEl.textContent.trim() : '';

		const groupData = {
			title: groupTitle,
			fields: []
		};

		groupEl.querySelectorAll('.dc-brief-form__field').forEach(fieldEl => {
			const labelEl = fieldEl.querySelector('.dc-brief-form__label')
				|| fieldEl.querySelector('.dc-brief-form__checkbox-one label');

			const label = labelEl ? labelEl.textContent.replace('*', '').trim() : '';

			let value = '';

			// ===== textarea =====
			const textarea = fieldEl.querySelector('textarea');
			if (textarea) {
				value = textarea.value.trim();
			}

			// ===== select =====
			const select = fieldEl.querySelector('select');
			if (select) {
				value = select.value;
			}

			// ===== input text/number =====
			const inputText = fieldEl.querySelector('input[type="text"], input[type="number"]');
			if (inputText) {
				value = inputText.value.trim();
			}

			// ===== single checkbox =====
			const singleCheckbox = fieldEl.querySelector('.dc-brief-form__checkbox-one input[type="checkbox"]');
			if (singleCheckbox) {
				value = singleCheckbox.checked ? 'Tak' : 'Nie';
			}

			// ===== radio =====
			const radios = fieldEl.querySelectorAll('.dc-brief-form__options--radio input[type="radio"]');
			if (radios.length) {
				const checked = Array.from(radios).find(r => r.checked);
				if (checked) {
					const optLabel = checked.closest('label')?.querySelector('span');
					value = optLabel ? optLabel.textContent.trim() : checked.value;
				} else {
					value = '';
				}
			}

			// ===== checkboxes (multi) =====
			const checkboxes = fieldEl.querySelectorAll('.dc-brief-form__options--checkboxes input[type="checkbox"]');
			if (checkboxes.length) {
				const checkedLabels = [];
				checkboxes.forEach(chk => {
					if (chk.checked) {
						const optLabel = chk.closest('label')?.querySelector('span');
						if (optLabel) checkedLabels.push(optLabel.textContent.trim());
					}
				});
				value = checkedLabels.join(', ');
			}

			// pomiń puste etykiety
			if (label !== '') {
				groupData.fields.push({
					label: label,
					value: value
				});
			}
		});

		if (groupData.fields.length) {
			result.push(groupData);
		}
	});

	return result;
}

async function dc_sendBrief(ev, root) {
	try {
		if (ev && typeof ev.preventDefault === "function") ev.preventDefault();

		if (!root) {
			console.error('DC BRIEF: root not provided');
			alert("Brief form not found.");
			return;
		}

		const modal = root.querySelector('.dc-brief__modal');
		if (!modal) {
			console.error('DC BRIEF: modal not found');
			alert("Brief modal not found.");
			return;
		}

		// === COLLECT BRIEF DATA ===
		const briefGroups = dc_getBrief(root);

		// === CONTACT FIELDS (if present) ===
		const nameInput  = modal.querySelector('[name="brief_name"]');
		const emailInput = modal.querySelector('[name="brief_email"]');
		const phoneInput = modal.querySelector('[name="brief_phone"]');

		const brief_name  = (nameInput?.value || '').trim();
		const brief_email = (emailInput?.value || '').trim();
		const brief_phone = (phoneInput?.value || '').trim();

		// === CSRF TOKEN (Joomla) ===
		const tokenInput = modal.querySelector('input[type="hidden"][name][value="1"]');

		// === PAYLOAD ===
		const payload = {
			brief_groups: briefGroups,
			brief_name: brief_name,
			brief_email: brief_email,
			brief_phone: brief_phone
		};

		console.log('DC BRIEF PAYLOAD:', payload);

		// === AJAX (your function) ===
		const out = await dc_postBrief(payload, tokenInput);

		console.log("DC BRIEF AJAX OUT:", out);

		if (out && out.success && out.data && out.data.ok) {
			alert("Thank you! Your brief has been sent successfully.");

			if (window.bootstrap) {
				const inst = window.bootstrap.Modal.getInstance(modal) || new window.bootstrap.Modal(modal);
				inst.hide();
			}
		} else {
			console.error(out);
			alert("Failed to send the brief. Please try again.");
		}
	} catch (err) {
		console.error('DC BRIEF ERROR:', err);
		alert("An unexpected error occurred while sending the brief.");
	}
}



/* ===== dc_modal.js (mtime: 2026-01-28T05:48:04+00:00) ===== */
(function () {
	"use strict";

	function openModal(modal) {
		if (!modal) return;
		modal.classList.add("is-open");
		document.documentElement.classList.add("dc-brief-lock");
	}

	function closeModal(modal) {
		if (!modal) return;
		modal.classList.remove("is-open");
		if (!document.querySelector(".dc-brief__modal.is-open")) {
			document.documentElement.classList.remove("dc-brief-lock");
		}
	}

	function getRootFromModal(modal) {
		// zakładamy: modal jest w wrapperze modułu z id=$uid
		return modal.closest("[id]") || null;
	}

	// OPEN: klik w przycisk z data-modal_id
	document.addEventListener("click", function (e) {
		const btn = e.target?.closest?.(".dc-mod-brief__open-btn");
		if (!btn) return;

		e.preventDefault();

		const modalId = btn.getAttribute("data-modal_id");
		const modal = modalId ? document.getElementById(modalId) : null;

		if (!modal) {
			alert("Brief modal not found.");
			return;
		}

		openModal(modal);
	});

	// CLOSE: backdrop / X / cancel (nie trzeba znać $uid)
	document.addEventListener("click", function (e) {
		const t = e.target;

		// backdrop
		const backdrop = t?.closest?.('.dc-brief__backdrop[data-close="1"]');
		if (backdrop) {
			e.preventDefault();
			closeModal(backdrop.closest(".dc-brief__modal"));
			return;
		}

		// X close
		const x = t?.closest?.(".dc-brief__close");
		if (x) {
			e.preventDefault();
			closeModal(x.closest(".dc-brief__modal"));
			return;
		}

		// cancel close
		const cancel = t?.closest?.(".dc-brief-modal__btn-cancel");
		if (cancel) {
			e.preventDefault();
			closeModal(cancel.closest(".dc-brief__modal"));
			return;
		}
	});

	// ESC: zamyka aktualnie otwarty modal (pierwszy znaleziony)
	document.addEventListener("keydown", function (e) {
		if (e.key !== "Escape") return;
		const modal = document.querySelector(".dc-brief__modal.is-open");
		if (modal) closeModal(modal);
	});

	// SUBMIT: validate -> collect -> send
	document.addEventListener("click", function (e) {
		const btn = e.target?.closest?.(".dc-brief-modal__btn-submit");
		if (!btn) return;

		e.preventDefault();

		const modal = btn.closest(".dc-brief__modal");
		if (!modal) {
			alert("Brief modal not found.");
			return;
		}

		const root = getRootFromModal(modal);
		if (!root) {
			alert("Brief module root not found.");
			return;
		}

		// form po data-form (pewne)
		const formId = btn.getAttribute("data-form");
		const form = formId ? document.getElementById(formId) : modal.querySelector("form");

		if (!form) {
			alert("Brief form not found.");
			return;
		}

		if (typeof window.dc_validateBrief === "function") {
			const ok = window.dc_validateBrief(root);
			if (!ok) return;
		}

		if (typeof window.dc_getBrief === "function") {
			window.dc_getBrief(root);
		}

		if (typeof window.dc_sendBrief === "function") {
			window.dc_sendBrief(e, root);
			return;
		}

		alert("Send function is not available.");
	});
})();


/* ===== dc_mail.js (mtime: 2026-01-28T11:47:44+00:00) ===== */
function dc_findFieldValue(groups, keys) {
	const wanted = new Set((keys || []).map(k => String(k).toLowerCase()));
	for (const g of groups || []) {
		for (const f of g.fields || []) {
			// tu mamy label/value; jeśli chcesz po id, musiałbyś dodać data-field-id w HTML
			// więc robimy heurystykę po label (email/name/phone) i ewentualnie po nazwie z value.
			const lbl = String(f.label || '').toLowerCase();
			if ([...wanted].some(k => lbl.includes(k))) {
				return String(f.value || '').trim();
			}
		}
	}
	return '';
}

async function dc_sendBrief(ev, root){
	try{
		if(ev && typeof ev.preventDefault === "function") ev.preventDefault();

		if(!root){
			alert("Brief form not found.");
			return;
		}

		const modal = root.querySelector('.dc-brief__modal');
		if(!modal){
			alert("Brief modal not found.");
			return;
		}

		const form = modal.querySelector('form.dc-brief-form');
		if(!form){
			alert("Brief form not found.");
			return;
		}

		// 1) zbierz dane brief
		const brief_groups = (typeof window.dc_getBrief === "function") ? window.dc_getBrief(root) : [];

		// 2) token Joomla (z formy / modala)
		const tokenInput = form.querySelector('input[type="hidden"][name][value="1"]')
			|| modal.querySelector('input[type="hidden"][name][value="1"]');

		// 3) payload (tylko nowe dane)
		const payload = { brief_groups };

		// UWAGA: przekazujemy FORM, nie root
		const out = await dc_postBrief(payload, tokenInput, form);

		console.log('DC MAIL AJAX OUT:', out);

		const successMsg = root.getAttribute('data-mail_success') 
			|| "Your brief has been sent successfully.";

		const errorMsg = root.getAttribute('data-mail_error') 
			|| "Failed to send the brief. Please try again.";

		// bardzo tolerancyjny warunek sukcesu (Joomla/com_ajax)
		const ok =
			(out && out.success === true && out.data && out.data.ok === true) ||
			(out && out.success === true && out.data && out.data.ok === 1) ||
			(out && out.success === true && out.data === true);

		if(ok){
			console.log('DC MAIL: SUCCESS');
			alert(successMsg);

			if(window.bootstrap){
				const inst = window.bootstrap.Modal.getInstance(modal) || new window.bootstrap.Modal(modal);
				inst.hide();
			}
		}else{
			console.log('DC MAIL: ERROR PATH');
			console.error(out);
			alert(errorMsg);
		}

	}catch(err){
		console.error(err);
		alert("An unexpected error occurred while sending the brief.");
	}
}


async function dc_postBrief(payload, tokenInput, form){
	const url = "index.php?option=com_ajax&module=dc_brief&method=sendBrief&format=json"; // dopasuj

	const fd = form ? new FormData(form) : new FormData();

	// dopnij JSON z grupami (to jest kluczowe dla nowego briefu)
	fd.set("brief_groups", JSON.stringify(payload.brief_groups || []));

	// zostawiamy opcjonalne pola (możesz je potem wywalić, jeśli backend nie używa)
	fd.set("brief_email", payload.brief_email || "");
	fd.set("brief_name", payload.brief_name || "");
	fd.set("brief_phone", payload.brief_phone || "");

	// token (jeśli nie zostałby złapany w FormData(form), to dopinamy)
	if(tokenInput && tokenInput.name){
		fd.set(tokenInput.name, "1");
	}

	const res = await fetch(url, { method: "POST", body: fd, credentials: "same-origin" });
	return await res.json();
}



