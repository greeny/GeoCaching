<?php

namespace GeoCaching\Database;

use Fabik\Database\ActiveRow;
use Fabik\Database\IRowFactory;
use Fabik\Database\ModelManager;
use GeoCaching\Model\Server;
use GeoCaching\Security\PasswordCrypter;
use Nette\Caching\IStorage;
use Nette\Database\Connection;
use Nette\Database\IReflection;
use Nette\Object;

/**
 * @property \GeoCaching\ServerModule\Model\Caches caches
 * @property \GeoCaching\ServerModule\Model\CacheScores cacheScores
 * @property \GeoCaching\ServerModule\Model\Users users
 * @author Tomáš Blatný
 */
class TableFactory extends Object {

	/** @var \Fabik\Database\IRowFactory  */
	protected $rowFactory;

	/** @var \Nette\Database\IReflection  */
	protected $reflection;

	/** @var \Nette\Caching\IStorage  */
	protected $cacheStorage;

	/** @var \Fabik\Database\ModelManager */
	protected $modelManager;

	/** @var array of Table */
	protected $tables = array();

	/** @var \GeoCaching\Model\Server */
	protected $server;

	public function __construct(IRowFactory $rowFactory, IReflection $reflection = NULL, IStorage $cacheStorage)
	{
		$this->rowFactory = $rowFactory;
		$this->reflection = $reflection;
		$this->cacheStorage = $cacheStorage;
	}

	public function setServer(ActiveRow $server)
	{
		$connection = new Connection('mysql:host=localhost;dbname=' . $server->database_name, $server->username, PasswordCrypter::decrypt($server->password));
		$this->modelManager = new ModelManager($connection, $this->rowFactory, $this->reflection, $this->cacheStorage);
		$this->server = $server;
	}

	/**
	 * @return Server
	 */
	public function getServer()
	{
		return $this->server;
	}

	/**
	 * @param string $name
	 * @return \GeoCaching\Model\Table
	 */
	public function getTable($name)
	{
		if(isset($this->tables[$name])) {
			return $this->tables[$name];
		} else {
			$name = 'GeoCaching\ServerModule\Model\\' . ucfirst($name);
			return new $name($this->modelManager);
		}
	}

	/**
	 * @return \GeoCaching\ServerModule\Model\Caches
	 */
	public function getCaches()
	{
		return $this->getTable('caches');
	}

	/**
	 * @return \GeoCaching\ServerModule\Model\CacheScores
	 */
	public function getCacheScores()
	{
		return $this->getTable('cacheScores');
	}

	/**
	 * @return \GeoCaching\ServerModule\Model\Users
	 */
	public function getUsers()
	{
		return $this->getTable('users');
	}
}