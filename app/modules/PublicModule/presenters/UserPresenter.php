<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\PublicModule;

use GeoCaching\BasePresenter;
use GeoCaching\Controls\Form;
use GeoCaching\Model\UserFacade;
use GeoCaching\PublicModule\Forms\LoginForm;
use GeoCaching\PublicModule\Forms\RegisterForm;
use GeoCaching\PublicModule\Forms\VerifyForm;
use GeoCaching\RegisterException;
use GeoCaching\Mails\VerifyMail;
use GeoCaching\VerifyException;
use Nette\ArrayHash;
use Nette\Security\AuthenticationException;

class UserPresenter extends BasePresenter {
	/** @var \GeoCaching\Model\UserFacade */
	protected $userFacade;

	public function inject(UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}

	public function createComponentRegisterForm()
	{
		$form = new RegisterForm();
		$form->onSuccess[] = $this->registerFormSuccess;
		return $form;
	}

	public function registerFormSuccess(Form $form)
	{
		$data = new ArrayHash();
		try {
			$data = $this->userFacade->registerUser($form->getValues());
		} catch(RegisterException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}

		$mail = new VerifyMail($this, 'verifyMail');
		$mail->template->data = $data;
		$mail->setSubject('Potvrzení registrace');

		$this->mailSender->send($mail, $data->email);

		$this->flashSuccess('Registrace proběhla úspěšně, prosím potvrďte svůj email.');
		$this->redirect('User:verify');
	}

	public function createComponentVerifyForm()
	{
		$form = new VerifyForm();
		$form->onSuccess[] = $this->verifyFormSuccess;
		$form->setDefaults(array('user' => $this->params['id']));
		return $form;
	}

	public function verifyFormSuccess(Form $form)
	{
		$v = $form->getValues();
		try {
			$this->userFacade->verifyUser($v->user, $v->verification_code);
		} catch (VerifyException $e) {
			$this->flashError($e->getMessage());
			$this->redirect("Dashboard:default");
		}

		$this->flashSuccess('Ověření proběhlo úspěšně, nyní se můžete přihlásit.');
		$this->redirect("User:login");
	}

	public function createComponentLoginForm()
	{
		$form = new LoginForm();
		$form->onSuccess[] = $this->loginFormSuccess;
		return $form;
	}

	public function loginFormSuccess(Form $form)
	{
		$v = $form->getValues();

		try {
			$this->user->login($v->name, $v->password);
		} catch (AuthenticationException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}

		if($v->permanent) {
			$this->user->setExpiration('+14 days', FALSE, TRUE);
		} else {
			$this->user->setExpiration('+2 hours', TRUE, TRUE);
		}

		$this->flashSuccess('Byl jsi přihlášen.');

		$this->redirect("Dashboard:default");
	}

	public function actionVerify($id, $verify = null)
	{
		if($verify !== null) {
			try {
				$this->userFacade->verifyUser($id, $verify);
			} catch (VerifyException $e) {
				$this->flashError($e->getMessage());
				$this->redirect("Dashboard:default");
			}

			$this->flashSuccess('Ověření proběhlo úspěšně, nyní se můžete přihlásit.');
			$this->redirect("User:login");
		}
	}
}