<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\PublicModule\Forms;

use GeoCaching\Controls\BaseForm;
use GeoCaching\Controls\Form;

class LoginForm extends BaseForm {
	protected function initializeForm(Form $form)
	{
		$form->addText('name', 'Přihlašovací jméno')
			->setRequired('Prosím zadej svoje jméno.');

		$form->addPassword('password', 'Heslo')
			->setRequired('Prosím zadej svoje heslo.');

		$form->addCheckbox('permanent', 'Trvalé přihlášení na tomto počítači');

		$form->addSubmit('login', 'Přihlásit se')
			->setAttribute('class', 'btn-primary');
	}
}