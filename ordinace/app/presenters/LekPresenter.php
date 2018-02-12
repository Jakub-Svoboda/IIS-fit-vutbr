<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class LekPresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShow($id_lek)
    {
        $lek = $this->database->table('lek')->get($id_lek);
		if (!$lek) {
			$this->error('Stránka nebyla nalezena');
		}
		$this->template->lek = $lek;
	}
	protected function createComponentLekDeleteForm($id_lek){
		
		$form = new Form; // means Nette\Application\UI\Form
		$form->addSubmit('send', 'Odstranit');
		$form->onSuccess[] = [$this, 'lekDeleteFormSucceeded'];
		return $form;
	}
	public function lekDeleteFormSucceeded($form, $values)
	{	
		$id_lek = $this->getParameter('id_lek');
		$this->database->table('lek')->where('id_lek = ?', $id_lek)->delete();
		$this->flashMessage('Lék byl úspěšně odstraněn.', 'success');
		$this->redirect('Leky:show');
		return $values;
	}
}