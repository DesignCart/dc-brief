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


