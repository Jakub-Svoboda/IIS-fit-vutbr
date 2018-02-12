<?php

namespace App\Presenters;
use Nette;
use App\Forms;
use Nette\Application\UI\Form;


class SignPresenter extends Nette\Application\UI\Presenter
{
	private $signUpFactory;
	private $signInFactory;
	public $database;
	
	public function __construct(Forms\SignInFormFactory $signInFactory, Forms\SignUpFormFactory $signUpFactory, Nette\Database\Context $database)
	{
		$this->signInFactory = $signInFactory;
		$this->signUpFactory = $signUpFactory;
		$this->database = $database;
	}

	protected function createComponentSignUpForm(): Form
	{
		return $this->signUpFactory->create(function () {
			$this->redirect('Homepage:');
		});
	}

	public function renderUp(){
		$this->template->users=$this->database->table('users')->where('NOT (username ?)', 'admin');
    }
	public function renderUser($username){
		$u = $this->database->table('users')->get($username);
		if (!$u) {
			$this->error('Stránka nebyla nalezena');
		}
		$this->template->u = $u;
		
	}
	protected function createComponentSignInForm(): Form
	{
		return $this->signInFactory->create(function () {
			$this->redirect('Homepage:');
		});
	}
	public function actionCreate()
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}
	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Odhlášení bylo úspěšné.');
		$this->redirect('Homepage:');
	}
	protected function createComponentUserDeleteForm($username){
		
		$form = new Form; // means Nette\Application\UI\Form
		$form->addSubmit('send', 'Odstranit');
		$form->onSuccess[] = [$this, 'userDeleteFormSucceeded'];
		return $form;
	}
	public function userDeleteFormSucceeded($form, $values)
	{	
		$username = $this->getParameter('username');
		$this->database->table('users')->where('id = ?', $username)->delete();
		$this->flashMessage('Uživatel byl úspěšně odstraněn.', 'success');
		$this->redirect('Sign:up');
		return $values;
	}
}