<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule\Forms;

use GeoCaching\Controls\BaseForm;
use GeoCaching\Controls\Form;

class ConnectForm extends BaseForm {

	protected function initializeForm(Form $form)
	{
		$form->addText('nick', 'Nick na serveru')
			->setRequired('Prosím zadej svůj nick na serveru.');
		$form->addSubmit('connect', 'Propojit')
			->setAttribute('class', 'btn-primary');
	}
}