<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\PublicModule\Forms;

use GeoCaching\Controls\BaseForm;
use GeoCaching\Controls\Form;

class AddDataForm extends BaseForm {

	protected function initializeForm(Form $form)
	{
		$form->addText('key', 'Název')->setRequired('Prosím zadej název.');
		$form->addText('value', 'Hodnota')->setRequired('Prosím zadej hodnotu.');
		$form->addSelect('type', 'Typ', array(
			'string' => 'Text',
			'int' => 'Číslo',
			'email' => 'E-mail',
			'web' => 'Web',
		))->setPrompt('- Zadej typ -')->setRequired('Prosím zadej typ.');
		$form->addSubmit('add', 'Přidat')->setAttribute('class', 'btn-primary');
	}
}