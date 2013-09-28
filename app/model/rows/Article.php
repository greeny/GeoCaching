<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Model;

class Article extends ActiveRow {
	public function getAuthor()
	{
		return $this->ref('users', 'user_id');
	}

	public function getTags()
	{
		return $this->related('article_tags', 'article_id')->order('tag ASC');
	}

	public function getComments()
	{
		return $this->related('article_comments', 'article_id')->order('time DESC');
	}
}