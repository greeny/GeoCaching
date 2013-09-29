<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\PublicModule;

use GeoCaching\Controls\Form;
use GeoCaching\Database\TableFactory;
use GeoCaching\Mails\RegisterServerMail;
use GeoCaching\Model\ServerFacade;
use GeoCaching\PublicModule\Forms\RegisterServerForm;
use GeoCaching\RegisterServerException;
use Nette\ArrayHash;

class ServersPresenter extends BasePublicPresenter {
	/** @var  \GeoCaching\Model\ServerFacade */
	protected $serverFacade;

	/** @var \GeoCaching\Database\TableFactory */
	protected $tableFactory;

	public function inject(ServerFacade $serverFacade, TableFactory $tableFactory)
	{
		$this->serverFacade = $serverFacade;
		$this->tableFactory = $tableFactory;
	}

	public function renderList()
	{
		$this->template->servers = $this->serverFacade->getServers();
	}

	public function actionRegister()
	{
		if(!$this->user->isLoggedIn()) {
			$this->flashError('Pro registraci nového serveru se musíš přihlásit.');
			$this->redirect('Servers:list');
		}
	}

	public function createComponentRegisterServerForm()
	{
		$form = new RegisterServerForm();
		$form->onSuccess[] = $this->registerServerFormSuccess;
		return $form;
	}

	public function registerServerFormSuccess(Form $form)
	{
		$v = new ArrayHash();
		try {
			$v = $this->serverFacade->registerServer($v = $form->getValues(), $this->user->id);
		} catch(RegisterServerException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}

		$this->tableFactory->createDatabase($v->username, $v->password, $v->database_name);

		$mail = new RegisterServerMail($this, 'registerServerMail');
		$mail->template->data = $v;
		$mail->template->username = $this->user->getIdentity()->name;
		$mail->setSubject('Potvrzení registrace');

		$this->mailSender->send($mail, $this->user->getIdentity()->email);

		$this->flashSuccess('Server úspěšně zaregistrován, zkontroluj si emailovou schránku.');
		$this->redirect(':Server:Dashboard:default', array('server' => $v->shortcut));
	}

	public function handleFavorite($id)
	{
		if($this->user->isLoggedIn()) {
			$this->serverFacade->addFavorite($this->user->id, $id);
			$this->flashSuccess('Server přidán do oblíbených.');
		}
		$this->refresh();
	}

	public function handleUnfavorite($id)
	{
		if($this->user->isLoggedIn()) {
			$this->serverFacade->removeFavorite($this->user->id, $id);
			$this->flashSuccess('Server odebrán z oblíbených.');
		}
		$this->refresh();
	}
}