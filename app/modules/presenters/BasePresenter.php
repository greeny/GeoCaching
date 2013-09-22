<?php

namespace GeoCaching;

use GeoCaching\Controls\MailSender;
use Nette\Application\UI\Presenter;
use Nette\Mail\IMailer;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{
	/** @var MailSender */
	protected $mailSender;

	public function handleLogout()
	{
		if($this->user->isLoggedIn()) {
			$this->user->logout(TRUE);
			$this->flashSuccess('Byl jsi odhlášen.');
		}
		$this->redirect(":Public:Dashboard:default");
	}

	public function injectBase(MailSender $mailSender)
	{
		$this->mailSender = $mailSender;
	}

	public function flashError($message)
	{
		return $this->flashMessage($message, 'danger');
	}

	public function flashSuccess($message)
	{
		return $this->flashMessage($message, 'success');
	}

	public function refresh() {
		$this->redirect('this');
	}
}
