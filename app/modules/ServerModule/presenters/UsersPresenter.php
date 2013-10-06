<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\ServerModule;

use GeoCaching\Controls\Form;
use GeoCaching\Model\UserFacade;
use GeoCaching\ServerModule\Forms\ConnectForm;
use Nette\Utils\Paginator;

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

	public function renderList($id, $order = 'nick', $orderType = 'ASC')
	{
		if($orderType !== 'ASC' && $orderType !== 'DESC') {
			$orderType = 'ASC';
		}
		$paginator = new Paginator();
		$paginator->setItemsPerPage(20);
		$paginator->setPage($id);
		$paginator->setItemCount($this->tableFactory->users->findAll()->count());
		if($id !== $paginator->getPage()) {
			$this->redirect('this', array($paginator->getPage()));
		}
		$this->template->users = $this->tableFactory->getConnection()
				->query("SELECT u.*,
						COALESCE(AVG(cs.score), 0) AS voting,
						(SELECT COUNT(*) FROM logs l WHERE l.user_id = u.id) AS found,
						(SELECT COUNT(*) FROM caches c WHERE c.owner = u.id) AS created
						FROM users u
						LEFT JOIN cache_score cs ON u.id = cs.user_id
						GROUP BY u.id
						ORDER BY `$order` $orderType
						LIMIT {$paginator->getOffset()}, {$paginator->getLength()}");
		if(true) {
		} else {
			$this->template->users = $this->tableFactory->users->findAll()->order($order.' '.$orderType)->limit($paginator->getLength(), $paginator->getOffset());
		}
		$this->template->paginator = $paginator;
		$this->template->order = $order;
		$this->template->orderType = $orderType;
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