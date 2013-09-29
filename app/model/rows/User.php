<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Model;

class User extends ActiveRow {
	public function getServerUserId($serverId)
	{
		$row = $this->related('user_servers', 'user_id')->where('server_id', $serverId)->limit(1)->fetch();
		return ($row ? $row->server_user_id : null);
	}

	public function getData()
	{
		return $this->related('user_data', 'user_id');
	}

	public function getFavoriteServers()
	{
		return $this->related('user_favorite_servers', 'user_id');
	}

	public function getFavoriteServersIds()
	{
		return $this->related('user_favorite_servers', 'user_id')->fetchPairs('id', 'server_id');
	}
}