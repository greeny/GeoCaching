<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\PublicModule;

use GeoCaching\Model\ServerFacade;

class ServersPresenter extends BasePublicPresenter {
	/** @var  \GeoCaching\Model\ServerFacade */
	protected $serverFacade;

	public function inject(ServerFacade $serverFacade)
	{
		$this->serverFacade = $serverFacade;
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
}