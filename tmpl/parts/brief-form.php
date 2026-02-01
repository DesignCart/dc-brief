<?php
	defined('_JEXEC') or die;

	$groupsRaw = $data['groups'] ?? [];
	$groups = json_decode(json_encode($groupsRaw), true) ?: [];

	if (empty($groups)) {
		return;
	}
?>

<form class="dc-brief-form" 
	id="<?php echo htmlspecialchars($uid, ENT_QUOTES, 'UTF-8'); ?>_form" 
	method="post" 
	action=""
	<?php if (!empty($data['captcha_v3_sitekey'])): ?>
		data-recaptcha-sitekey="<?php echo htmlspecialchars($data['captcha_v3_sitekey'], ENT_QUOTES, 'UTF-8'); ?>"
		data-recaptcha-action="<?php echo htmlspecialchars($data['captcha_v3_action'], ENT_QUOTES, 'UTF-8'); ?>"
	<?php endif; ?>
	>
	<?php foreach ($groups as $gIndex => $groupWrap): ?>
		<?php
			// U Ciebie: $groupWrap['group'] trzyma dane grupy
			$group = $groupWrap['group'] ?? [];

			$gTitle = trim((string) ($group['title'] ?? ''));
			$gDesc  = trim((string) ($group['description'] ?? ''));

			// U Ciebie: $group['fields'] ma fields0, fields1...
			$fieldsWrap = $group['fields'] ?? [];
			$fieldsWrap = is_array($fieldsWrap) ? $fieldsWrap : (json_decode(json_encode($fieldsWrap), true) ?: []);
			$fieldsItems = array_values($fieldsWrap); // z fields0 -> [0]
		?>

		<div class="dc-brief-form__group">
			<?php if ($gTitle !== ''): ?>
				<h4 class="dc-brief-form__group-title"><?php echo htmlspecialchars($gTitle, ENT_QUOTES, 'UTF-8'); ?></h4>
			<?php endif; ?>

			<?php if ($gDesc !== ''): ?>
				<div class="dc-brief-form__group-desc"><?php echo htmlspecialchars($gDesc, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php endif; ?>

			<?php if (!empty($fieldsItems)): ?>
				<div class="dc-brief-form__fields">
					<?php foreach ($fieldsItems as $fIndex => $fieldWrap): ?>
						<?php
							// U Ciebie: $fieldWrap['field'] trzyma dane pola
							$f = $fieldWrap['field'] ?? [];
							require __DIR__ . '/brief-field.php';
						?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

	<?php endforeach; ?>
	<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
	<input type="hidden" name="mail_to" value="<?php echo htmlspecialchars($data['mail_to'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
	<input type="hidden" name="mail_subject" value="<?php echo htmlspecialchars($data['mail_subject'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
	<input type="hidden" name="mail_intro" value="<?php echo htmlspecialchars($data['mail_intro'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
</form>


