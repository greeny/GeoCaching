<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule;

class DashboardPresenter extends BaseServerPresenter {

	public function renderDefault()
	{
		$this->template->caches = $this->tableFactory->caches->findAll()->count();
	}
}