<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule;

use GeoCaching\Controls\Form;
use GeoCaching\Model\UserFacade;
use GeoCaching\ServerModule\Forms\ConnectForm;

class UsersPresenter extends BaseServerPresenter {

	/** @var \GeoCaching\Model\UserFacade */
	protected $userFacade;

	public function inject(UserFacade $userFacade)
	{
		$this->userFacade = $userFacade;
	}

	public function renderDetail($id)
	{
		if(!$this->template->data = $this->tableFactory->users->findOneBy('nick', $id)) {
			$this->flashError('Uživatel neexistuje.');
			$this->redirect('Users:list');
		}
	}

	public function actionConnect()
	{
		if($this->serverUser) {
			$this->redirect('User:detail', array('id' => $this->serverUser->nick));
		}
	}

	public function createComponentConnectForm()
	{
		$form = new ConnectForm;
		$form->onSuccess[] = $this->connectFormSuccess;
		return $form;
	}

	public function connectFormSuccess(Form $form)
	{
		$v = $form->getValues();
		if(!$user = $this->tableFactory->users->findOneBy('nick', $v->nick)) {
			$this->flashError('Uživatel '.$v->nick.' nenalezen.');
			$this->refresh();
		}
		$this->userFacade->connect($this->user->identity->id, $user->id, $this->tableFactory->getServer()->id);
		$this->flashSuccess('Účty byly propojeny.');
		$this->redirect(':Server:Users:detail', array('id' => $v->nick));
	}

	public function renderList()
	{
		$this->template->users = $this->tableFactory->users->findAll()->order('nick ASC');
	}

	public function handlePromote($to)
	{
		static $array = array('admin', 'superadmin', 'owner');
		if(in_array($this->serverUser->role, $array)) {
			$user = $this->tableFactory->users->findOneBy('nick', $this->params['id']);
			if($user) {
				$user->update(array('role' => $to));
				$this->flashSuccess("Uživateli byla přiřazena role '".ucfirst($to)."'.");
			}
		}
		$this->refresh();
	}
}