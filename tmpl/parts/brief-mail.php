
<div class="modal fade" id="send-brief" tabindex="-1" aria-labelledby="send-brief-header" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-dialog-scrollable">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h3 class="modal-title fs-5" id="send-brief-header">Wyślij zapytanie z brief</h3>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
      		</div>
			<div class="modal-body">
				<form method="post">
					<div class="dc-quiz-brief-functions">
						<div>
							<strong>Odpowiedz jeszcze na kilka pytań ☺️</strong>
						
							<div class="mb-3">
								<label for="input-languages" class="col-form-label">Ilość wersji językowych</label>
								<div class="input-group">
									<input type="number" name="brief_languages" min="1" step="1" class="form-control" id="input-languages" value="Domyślnie. 1 wersja: polska">
								</div>
							</div>

							<div class="mb-3">
								<label for="input-payments" class="col-form-label">Wypisz wybrane metody płatności</label>
								<div class="input-group">
									<input type="text" name="brief_payments" class="form-control" id="input-payments" value="Domyślnie: PayU, Przelew, Za pobraniem">
								</div>
							</div>

							<div class="mb-3">
								<label for="brief_shippings" class="col-form-label">Wypisz wybrane metody wysyłki</label>
								<div class="input-group">
									<input type="text" name="brief_shippings" class="form-control" id="brief_shippings" value="Domyślnie: Kurier + Paczkomaty">
								</div>
							</div>

							<div class="mb-3">
								<label for="brief_functions" class="col-form-label">Wypisz dodatkowe funkcjonalności których potrzebujesz np.: integracja z baselinker, integracja z Comarch Optima itp.</label>
								<div class="input-group">
									<textarea type="text" name="brief_functions" class="form-control" id="brief_functions"></textarea>
								</div>
							</div>

							<div class="mb-3">
								<label for="brief_infos" class="col-form-label">Co jeszcze powinniśmy wiedzieć? Może które sklepy Ci się podobają. Możesz tu wkleić linki do inspiracji, konkurencji oraz wszelkie dodatkowe uwagi.</label>
								<div class="input-group">
									<textarea type="text" name="brief_infos" class="form-control" id="brief_infos"></textarea>
								</div>
							</div>
						</div>
					</div>

					<div class="dc-quiz-brief-contact">
						<div class="mb-3">
							<label for="brief_name" class="col-form-label">Imię</label>
							<div class="input-group">
								<input type="text" name="brief_name" class="form-control" id="brief_name" required >
							</div>
						</div>

						<div class="mb-3">
							<label for="brief_email" class="col-form-label">E-mail</label>
							<div class="input-group">
								<input type="email" name="brief_email" class="form-control" id="brief_email" required >
							</div>
						</div>

						<div class="mb-3">
							<label for="brief_phone" class="col-form-label">Telefon</label>
							<div class="input-group">
								<input type="text" name="brief_phone" class="form-control" id="brief_phone" required >
							</div>
						</div>
					</div>

					<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
				<button type="button" onclick="sendBrief(event);" class="btn btn-quiz">Wyślij brief</button>
			</div>
    	</div>
  	</div>
</div>