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
}