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

	public function getUserVote($id)
	{
		$row = $this->related('cache_score', 'cache_id')->where('user_id', $id)->fetch();
		return ($row ? $row->score: 0);
	}

	public function vote($userId, $score)
	{
		$this->related('cache_score', 'cache_id')->insert(array(
			'user_id' => $userId,
			'score' => $score,
			'cache_id' => $this->id,
			'time' => Time(),
		));
	}
}