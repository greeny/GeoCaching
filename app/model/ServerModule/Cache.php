<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule\Model;

use GeoCaching\Model\ActiveRow;

class Cache extends ActiveRow {
	const ACTIVE = 1;
	const APPROVAL = 2;
	const DISABLED = 3;

	public function getUser()
	{
		return $this->ref('users', 'owner');
	}

	public function getLogs()
	{
		return $this->related('logs', 'cache_id')->order('log_time DESC');
	}

	public function getFoundCount()
	{
		return $this->related('logs', 'cache_id')->count();
	}

	public function getScore()
	{
		return $this->related('cache_score', 'cache_id')->aggregation('AVG(score)');
	}

	public function getVotes(){
		return $this->related('cache_score', 'cache_id')->order('time DESC');
	}
}