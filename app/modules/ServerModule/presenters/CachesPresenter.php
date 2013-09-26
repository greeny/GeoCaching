<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule;

class CachesPresenter extends BaseServerPresenter {
	public function renderList()
	{
		$this->template->caches = $this->tableFactory->caches->findAll()->order('name ASC');
	}

	public function renderDetail($id)
	{
		if(!$this->template->cache = $cache = $this->tableFactory->caches->find($id)) {
			$this->flashError("Keška nenalezena.");
			$this->redirect("Caches:list");
		}
	}
}