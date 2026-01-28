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

