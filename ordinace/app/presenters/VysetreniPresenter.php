<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class VysetreniPresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShow($id_vysetreni)
    {
        $vysetreni = $this->database->table('vysetreni')->get($id_vysetreni);
        $pacient=$this->database->table('pacient')->get($vysetreni->id_pacient);
		if (!$vysetreni) {
			$this->error('Stránka nebyla nalezena');
		}
		$this->template->vysetreni = $vysetreni;
        $this->template->pacient = $pacient;
	}
	protected function createComponentVysetreniDeleteForm($id_vysetreni){
		
		$form = new Form; // means Nette\Application\UI\Form
		$form->addSubmit('send', 'Odstranit');
		$form->onSuccess[] = [$this, 'vysetreniDeleteFormSucceeded'];
		return $form;
	}
	public function vysetreniDeleteFormSucceeded($form, $values)
	{	
		$id_vysetreni = $this->getParameter('id_vysetreni');
		$this->database->table('vysetreni')->where('id_vysetreni = ?', $id_vysetreni)->delete();
		$this->flashMessage('Vyšetření bylo úspěšně odstraněno.', 'success');
		$this->redirect('Pacienti:show');
		return $values;
	}
}