<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Model;

use GeoCaching\Security\PasswordCrypter;
use Nette\ArrayHash;

class ServerFacade extends Facade {
	/** @var \GeoCaching\Model\Servers  */
	protected $servers;

	/** @var \GeoCaching\Model\ServersComments  */
	protected $serversComments;

	public function __construct(Servers $servers, ServersComments $serversComments)
	{
		$this->servers = $servers;
		$this->serversComments = $serversComments;
	}

	public function getServers()
	{
		return $this->servers->findAll()->where('active', TRUE);
	}

	public function registerServer(ArrayHash $data)
	{
		//check for unique keys, etc.

		//create database, etc.

		// add permissions and create user

		// add username, password, database name to $data

		$this->servers->insert(array(
			'name' => $data->name,
			'database_name' => $data->database_name = 'aaa',
			'username' => $data->username = 'username',
			'password' => $data->password = PasswordCrypter::encrypt('password'),
			'shortcut' => $data->shortcut,
			'ip' => $data->ip,
			'dynmap' => $data->dynmap,
			'description' => $data->description,
		));

		return $data;
	}
}