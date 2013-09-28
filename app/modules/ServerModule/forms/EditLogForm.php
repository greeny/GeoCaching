<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule\Forms;

use GeoCaching\Controls\BaseForm;
use GeoCaching\Controls\Form;

class EditLogForm extends BaseForm {
	protected function initializeForm(Form $form)
	{
		$form->addTextArea('text', 'Text');
		$form->addHidden('id');
		$form->addSubmit('edit', 'Upravit')
			->setAttribute('class', 'btn-primary');
	}

	public function render()
	{
		$this->setDefaults(func_get_arg(0));
		parent::render();
	}
}