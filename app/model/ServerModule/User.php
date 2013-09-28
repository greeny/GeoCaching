<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule\Model;

use GeoCaching\Model\ActiveRow;

class User extends ActiveRow {
	public function getFoundCount()
	{
		return $this->related('logs', 'user_id')->count();
	}

	public function getCreatedCount()
	{
		return $this->related('caches', 'owner')->count();
	}

	public function getAverageVoting()
	{
		return $this->related('cache_score', 'user_id')->aggregation('AVG(score)');
	}
}