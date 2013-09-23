<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule;

use GeoCaching\BasePresenter;
use GeoCaching\Database\TableFactory;
use GeoCaching\Model\Servers;

class BaseServerPresenter extends BasePresenter {

	/** @persistent string */
	protected $server;

	/** @var \GeoCaching\Database\TableFactory */
	protected $tableFactory;

	/** @var \GeoCaching\Model\Servers */
	protected $servers;

	public function injectBaseServer(TableFactory $tableFactory, Servers $servers)
	{
		$this->tableFactory = $tableFactory;
		$this->servers = $servers;
	}

	public function startup()
	{
		parent::startup();
		if(!$server = $this->servers->findOneBy('shortcut', $this->params['server'])) {
			$this->flashError('Server nenalezen.');
			$this->redirect(':Public:Dashboard:default');
		}

		$this->tableFactory->setServer($server);
	}
}