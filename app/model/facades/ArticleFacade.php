<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Model;

class ArticleFacade extends Facade {
	/** @var \GeoCaching\Model\Articles */
	protected $articles;

	public function __construct(Articles $articles)
	{
		$this->articles = $articles;
	}

	public function getNewestArticles()
	{
		return $this->articles->findAll()->order('time DESC')->limit(3);
	}

	public function findByName($name)
	{
		return $this->articles->findOneBy('shortcut', $name);
	}
}