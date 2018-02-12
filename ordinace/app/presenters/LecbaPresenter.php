<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class LecbaPresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShow($id_lecba)
    {
        $lecba = $this->database->table('lecba')->get($id_lecba);
        $lek = $this->database->table('lek')->get($lecba->id_lek);
        $pacient = $this->database->table('pacient')->get($lecba->id_pacient);
		if (!$lecba) {
			$this->error('Stránka nebyla nalezena');
		}
		$this->template->lecba = $lecba;
        $this->template->lek = $lek;
        $this->template->pacient = $pacient;
	}
	protected function createComponentLecbaDeleteForm($id_lecba){
		
		$form = new Form; // means Nette\Application\UI\Form
		$form->addSubmit('send', 'Odstranit');
		$form->onSuccess[] = [$this, 'lecbaDeleteFormSucceeded'];
		return $form;
	}
	public function lecbaDeleteFormSucceeded($form, $values)
	{	
		$id_lecba = $this->getParameter('id_lecba');
		$this->database->table('lecba')->where('id_lecba = ?', $id_lecba)->delete();
		$this->flashMessage('Léčba byla úspěšně odstraněna.', 'success');
		$this->redirect('Pacienti:show');
		return $values;
	}
}