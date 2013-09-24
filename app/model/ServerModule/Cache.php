<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule\Model;

use GeoCaching\Model\ActiveRow;

class Cache extends ActiveRow {
	public function getFoundCount()
	{
		return $this->related('logs', 'cache_id')->count();
	}

	public function getScore()
	{
		return $this->related('cache_score', 'cache_id')->aggregation('AVG(score)');
	}
}