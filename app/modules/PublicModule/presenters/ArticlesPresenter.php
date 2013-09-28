<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\PublicModule;

use GeoCaching\Model\ArticleFacade;
use GeoCaching\PublicModule\BasePublicPresenter;

class ArticlesPresenter extends BasePublicPresenter {
	/** @var \GeoCaching\Model\ArticleFacade */
	protected $articleFacade;

	public function inject(ArticleFacade $articleFacade)
	{
		$this->articleFacade = $articleFacade;
	}

	public function renderDetail($id)
	{
		if(!$this->template->article = $this->articleFacade->findByName($id)) {
			$this->flashError('Článek nenalezen.');
			$this->redirect('Dashboard:default');
		}
	}
}