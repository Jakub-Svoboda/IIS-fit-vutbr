<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class OsetrenisPresenter extends Nette\Application\UI\Presenter
{
	public $database;
	public function __construct(Nette\Database\Context $database)
    {
		$this->database = $database;
		
    }
	public function renderShow()
	{		
		
	}
	public function renderResult($datum, $pacient)
	{	if($datum==NULL and $pacient==null)$this->template->osetrenis=$this->database->table('osetreni');
		if($datum==NULL and $pacient!=null)$this->template->osetrenis=$this->database->table('osetreni')->where('id_pacient = ?', $pacient);
		if($datum!=NULL and $pacient==null)$this->template->osetrenis=$this->database->table('osetreni')->where('datum = ?', $datum);
		if($datum!=NULL and $pacient!=null)$this->template->osetrenis=$this->database->table('osetreni')->where('datum = ? AND id_pacient = ?', $datum, $pacient);		
	}
	protected function createComponentOsetreniForm()
	{
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('datum','Datum (YYYY-MM-DD):')->setDefaultValue(date("Y-m-d"));
		$form->addTextArea('popis', 'Popis:');
		$form->addText('id_pacient', '*ID pacienta:')->setRequired("Doplňte pacienta.");
		
		$form->addSubmit('send', 'Přidat ošetření');
		$form->onSuccess[] = [$this, 'osetreniFormSucceeded'];
		return $form;
	}
	public function osetreniFormSucceeded($form, $values)
	{
		//$id_lek = $this->getParameter('id_lek');

		$this->database->table('osetreni')->insert([
			'datum' => $values->datum,
			'popis' => $values->popis,
			'id_pacient' => $values->id_pacient
		]);

		$this->flashMessage('Úspěšně vloženo nové ošetření', 'success');
		$this->redirect('this');
	}
	protected function createComponentOsetreniSearchForm()
	{
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('datum', 'Datum:');
		$form->addText('id_pacient', 'ID pacienta:');
		
		$form->addSubmit('send', 'Vyhledat ošetření');
		$form->onSuccess[] = [$this, 'osetreniSearchFormSucceeded'];
		return $form;
	}
	public function osetreniSearchFormSucceeded($form, $values)
	{
		$this->redirect('Osetrenis:result', $values->datum, $values->id_pacient);
		return $values;
	}
	
}