<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Model;

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
}