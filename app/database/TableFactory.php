<?php

namespace GeoCaching\Database;

use Fabik\Database\ActiveRow;
use Fabik\Database\IRowFactory;
use Fabik\Database\ModelManager;
use GeoCaching\Model\Server;
use GeoCaching\Security\PasswordCrypter;
use Nette\Caching\IStorage;
use Nette\Database\Connection;
use Nette\Database\Helpers;
use Nette\Database\IReflection;
use Nette\Object;

/**
 * @property \GeoCaching\ServerModule\Model\Caches caches
 * @property \GeoCaching\ServerModule\Model\CacheScores cacheScores
 * @property \GeoCaching\ServerModule\Model\Users users
 * @property \GeoCaching\ServerModule\Model\Logs logs
 * @author Tomáš Blatný
 */
class TableFactory extends Object {

	/** @var string */
	protected $username;

	/** @var string */
	protected $password;

	/** @var \Fabik\Database\IRowFactory */
	protected $rowFactory;

	/** @var \Nette\Database\IReflection */
	protected $reflection;

	/** @var \Nette\Caching\IStorage */
	protected $cacheStorage;

	/** @var \Fabik\Database\ModelManager */
	protected $modelManager;

	/** @var array of Table */
	protected $tables = array();

	/** @var \Nette\Database\Connection */
	protected $connection;

	/** @var array */
	protected $connectionData;

	/** @var \GeoCaching\Model\Server */
	protected $server;

	public function __construct(IRowFactory $rowFactory, array $connectionData, Connection $connection, IReflection $reflection = NULL, IStorage $cacheStorage)
	{
		$this->username = $connectionData[0];
		$this->password = $connectionData[1];
		$this->connection = $connection;
		$this->rowFactory = $rowFactory;
		$this->reflection = $reflection;
		$this->cacheStorage = $cacheStorage;
	}

	public function setServer(ActiveRow $server)
	{
		$connection = new Connection('mysql:host=localhost;dbname=' . $server->database_name, $server->username, PasswordCrypter::decrypt($server->password));
		Helpers::createDebugPanel($connection);
		$this->modelManager = new ModelManager($connection, $this->rowFactory, $this->reflection, $this->cacheStorage);
		$this->server = $server;
	}

	public function createDatabase($username, $password, $dbname)
	{
		@set_time_limit(0);
		$password = PasswordCrypter::decrypt($password);
		$this->connection->query("CREATE DATABASE `$dbname` COLLATE 'utf8_general_ci';");
		$this->connection->query("CREATE USER '$username'@'localhost' IDENTIFIED BY '$password'");
		$this->connection->query("GRANT DELETE, INSERT, SELECT, UPDATE ON $dbname.* TO '$username'@'localhost'");
		$this->connection->query("CREATE USER '$username'@'%' IDENTIFIED BY '$password'");
		$this->connection->query("GRANT DELETE, INSERT, SELECT, UPDATE ON $dbname.* TO '$username'@'%'");
		$connection = new Connection("mysql:host=localhost;dbname=$dbname", $this->username, $this->password);
		Helpers::loadFromFile($connection, __DIR__.'/schema.sql');
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

	/**
	 * @return \GeoCaching\ServerModule\Model\Logs
	 */
	public function getLogs()
	{
		return $this->getTable('logs');
	}
}