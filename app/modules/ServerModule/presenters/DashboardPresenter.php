<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule;

class DashboardPresenter extends BaseServerPresenter {

	public function renderDefault()
	{
		$this->template->server = $this->tableFactory->getServer();
	}
}