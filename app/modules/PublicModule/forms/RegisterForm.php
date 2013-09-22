<?php
namespace GeoCaching\PublicModule\Forms;

use GeoCaching\Controls\BaseForm;
use GeoCaching\Controls\Form;

/**
 * @author Tomáš Blatný
 */

class RegisterForm extends BaseForm {
	protected function initializeForm(Form $form)
	{
		$form->addText('name', 'Přezdívka')
			->setRequired('Prosím zadej svoje jméno.')
			->addRule($form::PATTERN, 'Přezdívka musí mít minimálně 5 znaků, může obsahovat pouze písmena anglické abecedy, čísla a podtržítko.', '[a-zA-Z0-9_]{5,255}')
			->setOption('description', 'Přezdívka může být jakákoliv, na serveru pod ní nemusíš hrát. Později si zadáš, na kterém serveru máš jakou přezdívku.');

		$form->addPassword('password', 'Heslo')
			->setRequired('Prosím zadej svoje heslo.')
			->addRule($form::PATTERN, 'Heslo musí mít aspoň 5 znaků.', '.{5,255}');

		$form->addPassword('password_verify', 'Heslo pro kontrolu')
			->setRequired('Zadej prosím heslo znovu pro kontrolu překlepů.')
			->addRule($form::EQUAL, 'Hesla se musí shodovat.', $form['password']);

		$form->addText('email', 'Kontaktní email')
			->setRequired('Prosím zadej svůj email.')
			->addRule($form::EMAIL, 'Email není platný.');

		$form->addSubmit('register', 'Registrovat se')
			->setAttribute('class', 'btn-primary');
	}
}