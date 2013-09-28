<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Model;

use GeoCaching\Security\PasswordCrypter;
use Nette\ArrayHash;
use Nette\Utils\Strings;

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
		$server = $this->servers->createOrUpdate(array(
			'name' => $data->name,
			'database_name' => $data->database_name = 'database_x',
			'username' => $data->username = 'geocaching_x',
			'password' => $data->password = PasswordCrypter::encrypt(Strings::random(10, 'a-zA-Z0-9')),
			'shortcut' => $data->shortcut,
			'ip' => $data->ip,
			'dynmap' => $data->dynmap,
			'description' => $data->description,
		));
		$server->update(array(
			'database_name' => $data->database_name = 'geocaching_'.$server->id,
			'username' => $data->username = 'geocaching_'.$server->id,
		));
		return $data;
	}
}