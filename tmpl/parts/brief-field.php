<?php
    defined('_JEXEC') or die;

    $label = trim((string) ($f['label'] ?? ''));
    $idKey = trim((string) ($f['id'] ?? ''));
    $type  = trim((string) ($f['type'] ?? 'input'));
    $value = (string) ($f['value'] ?? '');
    $help  = trim((string) ($f['help'] ?? ''));
    $optionsRaw = (string) ($f['options'] ?? '');
    $required = (int) ($f['required'] ?? 0);
    $isRequired = ($required === 1 || $required === '1' || $required === true);

    $requiredAttr = $isRequired ? ' required aria-required="true"' : '';
    $requiredMark = $isRequired ? ' <span class="dc-brief-form__required">*</span>' : '';


    if ($idKey === '') {
        return;
    }

    // sanitize klucza do name/id (DOM i POST)
    $idKeySafe = preg_replace('/[^a-zA-Z0-9_-]+/', '_', $idKey) ?: ('field_' . (int) $fIndex);

    $inputIdBase = $uid . '_f_' . $idKeySafe;    // bazowy id w DOM
    $nameBase    = 'brief[' . $idKeySafe . ']';  // name do POST

    // Parse options for select/radio/checkboxes: "Label|value" or "Label"
    $parseOptions = static function (string $raw): array {
        $lines = preg_split('/\R/u', $raw) ?: [];
        $out = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $parts = array_map('trim', explode('|', $line, 2));
            $optLabel = (string) ($parts[0] ?? '');
            $optValue = (string) ($parts[1] ?? $optLabel);

            if ($optLabel !== '') {
                $out[] = ['label' => $optLabel, 'value' => $optValue];
            }
        }

        return $out;
    };

    $options = $parseOptions($optionsRaw);

    // default selections for multi-checkboxes from "value"
    $selectedMulti = [];
    if ($type === 'checkboxes') {
        $tmp = trim((string) $value);
        if ($tmp !== '') {
            $selectedMulti = preg_split('/\s*(?:,|\R)\s*/u', $tmp) ?: [];
            $selectedMulti = array_values(array_filter(array_map('trim', $selectedMulti), static fn($x) => $x !== ''));
        }
    }
?>

<div class="dc-brief-form__field dc-brief-form__field--<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>">
	<?php if ($type === 'checkbox'): ?>
		<?php
			// Single checkbox: checkbox + label in one line
			$checked = ($value === '1' || strtolower($value) === 'true' || strtolower($value) === 'yes' || strtolower($value) === 'on');
		?>
		<div class="dc-brief-form__checkbox-one">
			<input type="hidden" name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>" value="0" />
			<input type="checkbox" id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>" name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>" value="1" <?php echo $checked ? 'checked' : ''; ?> <?php echo $requiredAttr; ?> />
			<?php if ($label !== ''): ?>
				<label for="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?><<?php echo $requiredMark; ?>/label>
			<?php endif; ?>
		</div>

	<?php else: ?>
		<?php if ($label !== ''): ?>
			<label class="dc-brief-form__label" for="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?><?php echo $requiredMark; ?></label>
		<?php endif; ?>

		<?php if ($type === 'textarea'): ?>
			<textarea id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>" name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>" rows="4" <?php echo $requiredAttr; ?>><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></textarea>

		<?php elseif ($type === 'number'): ?>
			<input type="number" id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>" name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" step="any" <?php echo $requiredAttr; ?> />

		<?php elseif ($type === 'integer'): ?>
			<input type="number" id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>" name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" min="0" step="1" <?php echo $requiredAttr; ?> />

		<?php elseif ($type === 'select'): ?>
			<select id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>" name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $requiredAttr; ?>>
				<?php foreach ($options as $opt): ?>
					<?php $sel = ((string) $value !== '' && (string) $value === (string) $opt['value']) ? 'selected' : ''; ?>
					<option value="<?php echo htmlspecialchars($opt['value'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($opt['label'], ENT_QUOTES, 'UTF-8'); ?></option>
				<?php endforeach; ?>
			</select>

		<?php elseif ($type === 'radio'): ?>
			<div class="dc-brief-form__options dc-brief-form__options--radio">
				<?php foreach ($options as $i => $opt): ?>
					<?php $rid = $inputIdBase . '_r_' . $i; ?>
					<label class="dc-brief-form__option" for="<?php echo htmlspecialchars($rid, ENT_QUOTES, 'UTF-8'); ?>">
						<input type="radio" id="<?php echo htmlspecialchars($rid, ENT_QUOTES, 'UTF-8'); ?>" name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars($opt['value'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo ((string) $value !== '' && (string) $value === (string) $opt['value']) ? 'checked' : ''; ?> <?php echo $requiredAttr; ?>/>
						<span><?php echo htmlspecialchars($opt['label'], ENT_QUOTES, 'UTF-8'); ?></span>
					</label>
				<?php endforeach; ?>
			</div>

		<?php elseif ($type === 'checkboxes'): ?>
			<?php $nameMulti = 'brief[' . $idKeySafe . '][]'; ?>
			<div class="dc-brief-form__options dc-brief-form__options--checkboxes">
				<?php foreach ($options as $i => $opt): ?>
					<?php
						$cid = $inputIdBase . '_c_' . $i;
						$isChecked = in_array((string) $opt['value'], $selectedMulti, true);
					?>
					<label class="dc-brief-form__option" for="<?php echo htmlspecialchars($cid, ENT_QUOTES, 'UTF-8'); ?>">
						<input type="checkbox" id="<?php echo htmlspecialchars($cid, ENT_QUOTES, 'UTF-8'); ?>" name="<?php echo htmlspecialchars($nameMulti, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars($opt['value'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $isChecked ? 'checked' : ''; ?> <?php echo $requiredAttr; ?>/>
						<span><?php echo htmlspecialchars($opt['label'], ENT_QUOTES, 'UTF-8'); ?></span>
					</label>
				<?php endforeach; ?>
			</div>

		<?php else: ?>
			<!-- input (text) fallback -->
			<input type="text" id="<?php echo htmlspecialchars($inputIdBase, ENT_QUOTES, 'UTF-8'); ?>" name="<?php echo htmlspecialchars($nameBase, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $requiredAttr; ?>/>
		<?php endif; ?>
	<?php endif; ?>
</div>
<?php if ($help !== ''): ?>
	<div class="dc-brief-form__help">💡 <?php echo htmlspecialchars($help, ENT_QUOTES, 'UTF-8'); ?></div>
<?php endif; ?>
