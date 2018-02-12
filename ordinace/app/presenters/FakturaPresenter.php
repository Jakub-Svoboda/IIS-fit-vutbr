<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class FakturaPresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context */
    private $database;
	private $id_faktura;
	
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShow($id_faktura)
    {
        $this->id_faktura = $faktura = $this->database->table('faktura')->get($id_faktura);
        $nazev=$this->database->table('pojistovna')->get($faktura->id_pojistovna);
        $pacient=$this->database->table('pacient')->get($faktura->id_pacient);
		if (!$faktura) {
			$this->error('Stránka nebyla nalezena');
		}
		$this->template->faktura = $faktura;
        $this->template->nazev = $nazev;
        $this->template->pacient = $pacient;
	}
	protected function createComponentFakturaDeleteForm($id_faktura){
		
		$form = new Form; // means Nette\Application\UI\Form
		$form->addSubmit('send', 'Odstranit');
		$form->onSuccess[] = [$this, 'fakturaDeleteFormSucceeded'];
		return $form;
	}
	public function fakturaDeleteFormSucceeded($form, $values)
	{	
		$id_faktura = $this->getParameter('id_faktura');
		$this->database->table('faktura')->where('id_faktura = ?', $id_faktura)->delete();
		$this->flashMessage('Faktura byla úspěšně odstraněna.', 'success');
		$this->redirect('Faktury:show');
		return $values;
	}
}