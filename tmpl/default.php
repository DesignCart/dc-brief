<?php
	defined('_JEXEC') or die;

	use Joomla\CMS\Factory;
	

	$doc = Factory::getApplication()->getDocument();

	$uid = 'dcb_' . (int) $module->id;
?>

<link id="dc-brief-css-<?= $uid ?>" href="<?= $cssUrl ?>?v=<?= $ver ?>" rel="stylesheet">

<?php require __DIR__ . '/parts/brief-css.php'; ?>

<div id="<?php echo $uid; ?>" 
	class="dc-mod-brief" 
	style="<?= $styleAttr ?>" 
	data-input-error="<?= htmlspecialchars($data['input_error'], ENT_QUOTES, 'UTF-8'); ?>" 
	data-mail_success="<?= htmlspecialchars($data['mail_success'], ENT_QUOTES, 'UTF-8'); ?>" 
	data-mail_error="<?= htmlspecialchars($data['mail_error'], ENT_QUOTES, 'UTF-8'); ?>" 
	>
	<?php require __DIR__ . '/parts/brief-hero.php'; ?>
	<?php require __DIR__ . '/parts/brief-modal.php'; ?>
</div>

<script>
	(function(){
		const root = document.getElementById('<?php echo htmlspecialchars($uid, ENT_QUOTES, "UTF-8"); ?>');
		if(!root) return;

		const btnSubmit = root.querySelector('.dc-brief-modal__btn-submit');
		if(!btnSubmit) return;

		btnSubmit.addEventListener('click', function(ev){
			ev.preventDefault();

			// 1) walidacja
			if(typeof window.dc_validateBrief === 'function'){
				const ok = window.dc_validateBrief(root);
				if(!ok) return;
			}

			// 2) zbiórka (opcjonalnie)
			if(typeof window.dc_getBrief === 'function'){
				window.dc_getBrief(root);
			}

			// 3) wysyłka
			if(typeof window.dc_sendBrief === 'function'){
				window.dc_sendBrief(ev, root);
				return;
			}

			alert("Send function is not available.");
		});
	})();
</script>



