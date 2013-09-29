<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule;

use GeoCaching\Controls\Form;
use GeoCaching\ServerModule\Forms\EditLogForm;
use GeoCaching\ServerModule\Model\Cache;

class CachesPresenter extends BaseServerPresenter {
	public function renderList()
	{
		$this->template->caches = $this->tableFactory->caches->findAll()->order('name ASC');
	}

	public function renderDetail($id)
	{
		if(!$this->template->cache = $cache = $this->tableFactory->caches->findOneBy('name', $id)) {
			$this->flashError("Keška nenalezena.");
			$this->redirect("Caches:list");
		}
	}

	public function createComponentEditLogForm()
	{
		$form = new EditLogForm();
		$form->onSuccess[] = $this->editLogFormSuccess;
		return $form;
	}

	public function editLogFormSuccess(Form $form)
	{
		$v = $form->values;
		$log = $this->tableFactory->logs->find($v->id);
		if($log) {
			$log->update(array('text' => $v->text));
			$this->flashSuccess('Log byl upraven.');
		}
		$this->refresh();
	}

	public function handleVote($score)
	{
		$cache = $this->tableFactory->caches->findOneBy('name', $this->params['id']);
		if($cache && $this->serverUser) {
			$cache->vote($this->serverUser->id, $score);
			$this->flashSuccess('Hodnocení bylo přidáno.');
		}
		$this->refresh();
	}

	public function handleActive()
	{
		static $array = array('moderator', 'admin', 'superadmin', 'owner');
		if(in_array($this->serverUser->role, $array)) {
			$cache = $this->tableFactory->caches->findOneBy('name', $this->params['id']);
			if($cache) {
				$cache->update(array('status' => Cache::ACTIVE));
				$this->flashSuccess('Keška byla nastavena jako aktivní.');
			}
		}
		$this->refresh();
	}

	public function handleApproval()
	{
		static $array = array('moderator', 'admin', 'superadmin', 'owner');
		if(in_array($this->serverUser->role, $array)) {
			$cache = $this->tableFactory->caches->findOneBy('name', $this->params['id']);
			if($cache) {
				$cache->update(array('status' => Cache::APPROVAL));
				$this->flashSuccess('Keška byla nastavena jako čekající na ověření.');
			}
		}
		$this->refresh();
	}

	public function handleDisabled()
	{
		static $array = array('moderator', 'admin', 'superadmin', 'owner');
		if(in_array($this->serverUser->role, $array)) {
			$cache = $this->tableFactory->caches->findOneBy('name', $this->params['id']);
			if($cache) {
				$cache->update(array('status' => Cache::DISABLED));
				$this->flashSuccess('Keška byla nastavena jako neaktivní.');
			}
		}
		$this->refresh();
	}
}