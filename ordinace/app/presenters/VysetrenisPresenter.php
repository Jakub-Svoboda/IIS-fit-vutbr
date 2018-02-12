<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class VysetrenisPresenter extends Nette\Application\UI\Presenter
{
	public $database;
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
			$this->template->vysetrenis=$this->database->table('vysetreni');
		}else{
			$this->template->vysetrenis=$this->database->table('vysetreni')->where('nazev = ?', $nazev);
		}	
	}
	protected function createComponentVysetreniForm()
	{
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('nazev', '*Název:')->setRequired("Doplňte název.");
		$form->addText('id_pacient', '*ID pacienta:')->setRequired("Doplňte pacienta.");
		$form->addSubmit('send', 'Přidat vyšetření');
		$form->onSuccess[] = [$this, 'vysetreniFormSucceeded'];
		return $form;
	}
	public function vysetreniFormSucceeded($form, $values)
	{
		$this->database->table('vysetreni')->insert([
			'nazev' => $values->nazev,
			'id_pacient' => $values->id_pacient
		]);

		$this->flashMessage('Úspěšně vloženo nové vyšetření', 'success');
		$this->redirect('this');
	}
	protected function createComponentVysetreniSearchForm()
	{
		
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('nazev', 'Název:');
		$form->addSubmit('send', 'Vyhledat vyšetření');
		$form->onSuccess[] = [$this, 'vysetreniSearchFormSucceeded'];
		
		return $form;
		
	}
	public function vysetreniSearchFormSucceeded($form, $values)
	{		
		
		$this->redirect('Vysetrenis:result', $values->nazev);
		return $values;
	}
	
}