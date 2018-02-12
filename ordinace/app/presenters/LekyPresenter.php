<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class LekyPresenter extends Nette\Application\UI\Presenter
{
	public $database;
	private $nazev;
	public function __construct(Nette\Database\Context $database)
    {
		$this->database = $database;
		
    }
	public function renderShow()
	{		
		
	}
	public function renderSearch()
	{		
		
	}
	public function renderResult($nazev)
	{	
		if($nazev == NULL){
			$this->template->leky=$this->database->table('lek');
		}else{
			$this->template->leky=$this->database->table('lek')->where('nazev', $nazev);
		}	
	}
	protected function createComponentLekyForm()
	{
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('nazev', '*Název:')->setRequired("Doplňte název.");
		$form->addCheckbox('predpis', 'Vyžaduje předpis');
		$form->addTextArea('popis', 'Popis:');
		$form->addSubmit('send', 'Přidat Lek');
		$form->onSuccess[] = [$this, 'lekyFormSucceeded'];
		return $form;
	}
	public function lekyFormSucceeded($form, $values)
	{
		$id_lek = $this->getParameter('id_lek');

		$this->database->table('lek')->insert([
			'id_lek' => $id_lek,
			'nazev' => $values->nazev,
			'predpis' => $values->predpis,
		]);

		$this->flashMessage('Úspěšně vložen nový lék', 'success');
		$this->redirect('this');
	}
	protected function createComponentLekySearchForm()
	{
		$ids = $this->database->table('lek');
		$nazevlist=[];
		foreach ($ids as $id) {
			array_push($nazevlist,$id->nazev);
		}
		$form = new Form; // means Nette\Application\UI\Form
		
		$form->addSelect('nazev', 'Název:',$nazevlist)->setItems($nazevlist, false)->setPrompt('Zvolte lék');
		$form->addSubmit('send', 'Vyhledat lék');
		$form->onSuccess[] = [$this, 'lekySearchFormSucceeded'];
		return $form;
	}
	public function lekySearchFormSucceeded($form, $values)
	{
		$this->nazev=$values->nazev;		
		$this->redirect('Leky:result', $this->nazev);
		return $values;
	}
	
}