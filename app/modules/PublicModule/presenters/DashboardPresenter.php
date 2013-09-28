<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\PublicModule;

use GeoCaching\Model\ArticleFacade;

class DashboardPresenter extends BasePublicPresenter {

	/** @var \GeoCaching\Model\ArticleFacade */
	protected $articleFacade;

	public function inject(ArticleFacade $articleFacade)
	{
		$this->articleFacade = $articleFacade;
	}

	public function renderDefault()
	{
		$this->template->articles = $this->articleFacade->getNewestArticles();
	}
}