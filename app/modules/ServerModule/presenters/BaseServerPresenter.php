<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule;

use GeoCaching\BasePresenter;
use GeoCaching\Database\TableFactory;
use GeoCaching\Model\Servers;
use GeoCaching\Model\Users;
use Nette\Database\Helpers;

class BaseServerPresenter extends BasePresenter {

	/** @persistent string */
	public $server;

	/** @var \GeoCaching\Database\TableFactory */
	protected $tableFactory;

	/** @var \GeoCaching\Model\Servers */
	protected $servers;

	/** @var \GeoCaching\Model\Users */
	protected $users;

	/** @var \GeoCaching\ServerModule\Model\User */
	protected $serverUser;

	public function injectBaseServer(TableFactory $tableFactory, Servers $servers, Users $users)
	{
		$this->tableFactory = $tableFactory;
		$this->servers = $servers;
		$this->users = $users;
	}

	public function startup()
	{
		parent::startup();
		if(!$server = $this->servers->findOneBy('shortcut', $this->params['server'])) {
			$this->flashError('Server nenalezen.');
			$this->redirect(':Public:Dashboard:default');
		}

		$this->tableFactory->setServer($server);

		if($this->user->isLoggedIn()) {
			$this->serverUser = $this->tableFactory->users->find($this->users->find($this->user->id)->getServerUserId($server->id));
		} else {
			$this->serverUser = NULL;
		}
	}

	public function beforeRender()
	{
		parent::beforeRender();
		$this->template->server = $this->tableFactory->getServer();
		$this->template->serverUser = $this->serverUser;
	}
}