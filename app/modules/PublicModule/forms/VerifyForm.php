<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\PublicModule\Forms;

use GeoCaching\Controls\BaseForm;
use GeoCaching\Controls\Form;

class VerifyForm extends BaseForm {
	protected function initializeForm(Form $form)
	{
		$form->addText('verification_code', 'Ověřovací kód');

		$form->addHidden('user');

		$form->addSubmit('verify', 'Potvrdit')
			->setAttribute('class', 'btn-primary');
	}
}