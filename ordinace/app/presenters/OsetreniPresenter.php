<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class OsetreniPresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context */
    private $database;
	
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShow($id_osetreni)
    {
        $osetreni = $this->database->table('osetreni')->get($id_osetreni);
		if (!$osetreni) {
			$this->error('Stránka nebyla nalezena');
		}
		$this->template->osetreni = $osetreni;
	}
	protected function createComponentOsetreniDeleteForm($id_osetreni){
		
		$form = new Form; // means Nette\Application\UI\Form
		$form->addSubmit('send', 'Odstranit');
		$form->onSuccess[] = [$this, 'osetreniDeleteFormSucceeded'];
		return $form;
	}
	public function osetreniDeleteFormSucceeded($form, $values)
	{	
		$id_osetreni = $this->getParameter('id_osetreni');
		$this->database->table('osetreni')->where('id_osetreni = ?', $id_osetreni)->delete();
		$this->flashMessage('Ošetření bylo úspěšně odstraněno.', 'success');
		$this->redirect('Pacienti:show');
		return $values;
	}
}