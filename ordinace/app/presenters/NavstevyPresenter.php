<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class NavstevyPresenter extends Nette\Application\UI\Presenter
{
	public $database;
	public function __construct(Nette\Database\Context $database)
    {
		$this->database = $database;
		
    }
	public function renderShow()
	{		
		
	}
	public function renderResult($datum, $id_pacient)
	{	if($datum==NULL and $id_pacient==null)$this->template->navstevy=$this->database->table('navsteva');
		if($datum==NULL and $id_pacient!=null)$this->template->navstevy=$this->database->table('navsteva')->where('id_pacient = ?', $id_pacient);
		if($datum!=NULL and $id_pacient==null)$this->template->navstevy=$this->database->table('navsteva')->where('datum = ?', $datum);
		if($datum!=NULL and $id_pacient!=null)$this->template->navstevy=$this->database->table('navsteva')->where('datum = ? AND id_pacient = ?', $datum, $id_pacient);
	}
	protected function createComponentNavstevaForm()
	{
		$ids = $this->database->table('pacient');
		$idlist=[];
		$jmenolist=[];
		$prijmenilist=[];
		foreach ($ids as $id) {
			array_push($idlist,$id->id_pacient);
			array_push($jmenolist,$id->jmeno);
			array_push($prijmenilist,$id->prijmeni);
		}
		$jmenolist = array_map(function($x, $y) { return $x . ' ' . $y; }, $jmenolist, $prijmenilist);
		$resultlist=array_combine($idlist,$jmenolist);
		
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('datum', 'Datum (YYYY-MM-DD):')->setDefaultValue(date("Y-m-d"))->addRule($form::PATTERN, 'Format must be YYYY-MM-DD', '[0-9]{4}-[0-9]{2}-[0-9]{2}')->setRequired(FALSE);
		$form->addTextArea('popis', 'Popis:');
		$form->addSelect('id_pacient', '*Pacient:',$resultlist)->setRequired("Doplňte ID pacienta")->setPrompt('Zvolte pacienta');

		$form->addSubmit('send', 'Přidat návštěvu');
		$form->onSuccess[] = [$this, 'navstevaFormSucceeded'];
		return $form;
	}
	public function navstevaFormSucceeded($form, $values)
	{
		//$id_lek = $this->getParameter('id_lek');

		$this->database->table('navsteva')->insert([
			'datum' => $values->datum,
			'popis' => $values->popis,
			'id_pacient' => $values->id_pacient
		]);

		$this->flashMessage('Úspěšně vložena nová návštěva', 'success');
		$this->redirect('Navstevy:show');
	}
	protected function createComponentNavstevaSearchForm()
	{
		$ids = $this->database->table('pacient');
		$idlist=[];
		$jmenolist=[];
		$prijmenilist=[];
		foreach ($ids as $id) {
			array_push($idlist,$id->id_pacient);
			array_push($jmenolist,$id->jmeno);
			array_push($prijmenilist,$id->prijmeni);
		}
		$jmenolist = array_map(function($x, $y) { return $x . ' ' . $y; }, $jmenolist, $prijmenilist);
		$resultlist=array_combine($idlist,$jmenolist);
		
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('datum', 'Datum (YYYY-MM-DD):')->setDefaultValue(date("Y-m-d"))->addRule($form::PATTERN, 'Format must be YYYY-MM-DD', '[0-9]{4}-[0-9]{2}-[0-9]{2}')->setRequired(FALSE);
		$form->addSelect('id_pacient', 'Pacient:',$resultlist)->setPrompt('Zvolte pacienta');
		
		$form->addSubmit('send', 'Vyhledat návštěvu');
		$form->onSuccess[] = [$this, 'navstevaSearchFormSucceeded'];
		return $form;
	}
	public function navstevaSearchFormSucceeded($form, $values)
	{
		$this->redirect('Navstevy:result', $values->datum, $values->id_pacient);
	}
	
}