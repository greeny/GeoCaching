<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule\Model;

use GeoCaching\Model\ActiveRow;

class User extends ActiveRow {
	public function getFound()
	{
		return $this->related('logs', 'user_id')->count();
	}
}