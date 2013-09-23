<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\PublicModule\Forms;

use GeoCaching\Controls\BaseForm;
use GeoCaching\Controls\Form;

class RegisterServerForm extends BaseForm {

	protected function initializeForm(Form $form)
	{
		$form->addText('name', 'Jméno serveru')
			->setRequired('Prosím zadej jméno serveru.');

		$form->addText('shortcut', 'Zkratka')
			->setRequired('Prosím zadej zkratku serveru.')
			->addRule($form::PATTERN, 'Zkratka může obsahovat pouze malá písmena anglické abecedy, čísla a pomlčku.', '[a-z0-9\-]{1,255}');

		$form->addText('ip', 'IP adresa serveru (nepovinné)');

		$form->addText('dynmap', 'Adresa DynMap (nepovinné)');

		$form->addTextArea('description', 'Popis')
			->setAttribute('rows', 5);

		$form->addSubmit('registerServer', 'Registrovat server')
			->setAttribute('class', 'btn-primary');
	}
}