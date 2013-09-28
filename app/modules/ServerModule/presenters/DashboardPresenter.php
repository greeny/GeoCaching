<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule;

class DashboardPresenter extends BaseServerPresenter {

	public function renderDefault()
	{
		$this->template->cacheCount = $this->tableFactory->caches->findAll()->count();
		$this->template->caches = $this->tableFactory->caches->findAll()->order('time DESC')->limit(3);
	}
}