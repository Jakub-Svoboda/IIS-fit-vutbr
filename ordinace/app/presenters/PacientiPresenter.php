<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class PacientiPresenter extends Nette\Application\UI\Presenter
{
	public $database;
	public function __construct(Nette\Database\Context $database)
    {
		$this->database = $database;
		
    }
	public function renderShow()
	{		
		
	}
	public function renderResult($adresa, $pohlavi, $prijmeni, $jmeno)
	{	if($adresa==NULL and $pohlavi==NULL and $prijmeni==NULL and $jmeno==null)$this->template->pacienti=$this->database->table('pacient');
		if($adresa==NULL and $pohlavi==NULL and $prijmeni==NULL and $jmeno!=null)$this->template->pacienti=$this->database->table('pacient')->where('jmeno = ?', $jmeno);
		if($adresa==NULL and $pohlavi==NULL and $prijmeni!=NULL and $jmeno==null)$this->template->pacienti=$this->database->table('pacient')->where('prijmeni = ?', $prijmeni);
		if($adresa==NULL and $pohlavi==NULL and $prijmeni!=NULL and $jmeno!=null)$this->template->pacienti=$this->database->table('pacient')->where('prijmeni = ? AND jmeno = ?', $prijmeni, $jmeno);
		if($adresa==NULL and $pohlavi!=NULL and $prijmeni==NULL and $jmeno==null)$this->template->pacienti=$this->database->table('pacient')->where('pohlavi = ?', $pohlavi);	
		if($adresa==NULL and $pohlavi!=NULL and $prijmeni==NULL and $jmeno!=null)$this->template->pacienti=$this->database->table('pacient')->where('jmeno = ? AND pohlavi = ?', $jmeno, $pohlavi);
		if($adresa==NULL and $pohlavi!=NULL and $prijmeni!=NULL and $jmeno==null)$this->template->pacienti=$this->database->table('pacient')->where('pohlavi = ? AND prijmeni = ?', $pohlavi, $prijmeni);
		if($adresa==NULL and $pohlavi!=NULL and $prijmeni!=NULL and $jmeno!=null)$this->template->pacienti=$this->database->table('pacient')->where('pohlavi = ? AND prijmeni = ? AND jmeno = ?', $pohlavi, $prijmeni, $jmeno);
		if($adresa!=NULL and $pohlavi==NULL and $prijmeni==NULL and $jmeno==null)$this->template->pacienti=$this->database->table('pacient')->where('adresa = ?', $adresa);
		if($adresa!=NULL and $pohlavi==NULL and $prijmeni==NULL and $jmeno!=null)$this->template->pacienti=$this->database->table('pacient')->where('adresa = ? AND jmeno = ?', $adresa, $jmeno);
		if($adresa!=NULL and $pohlavi==NULL and $prijmeni!=NULL and $jmeno==null)$this->template->pacienti=$this->database->table('pacient')->where('adresa = ? AND prijmeni = ?', $adresa, $prijmeni);
		if($adresa!=NULL and $pohlavi==NULL and $prijmeni!=NULL and $jmeno!=null)$this->template->pacienti=$this->database->table('pacient')->where('adresa = ? AND prijmeni = ? AND jmeno = ?', $adresa, $prijmeni, $jmeno);
		if($adresa!=NULL and $pohlavi!=NULL and $prijmeni==NULL and $jmeno==null)$this->template->pacienti=$this->database->table('pacient')->where('adresa = ? AND pohlavi = ?', $adresa, $pohlavi);	
		if($adresa!=NULL and $pohlavi!=NULL and $prijmeni==NULL and $jmeno!=null)$this->template->pacienti=$this->database->table('pacient')->where('adresa = ? AND jmeno = ? AND pohlavi = ?', $adresa, $jmeno, $pohlavi);
		if($adresa!=NULL and $pohlavi!=NULL and $prijmeni!=NULL and $jmeno==null)$this->template->pacienti=$this->database->table('pacient')->where('adresa = ? AND pohlavi = ? AND prijmeni = ?', $adresa, $pohlavi, $prijmeni);
		if($adresa!=NULL and $pohlavi!=NULL and $prijmeni!=NULL and $jmeno!=null)$this->template->pacienti=$this->database->table('pacient')->where('adresa = ? AND pohlavi = ? AND prijmeni = ? AND jmeno = ?', $adresa, $pohlavi, $prijmeni, $jmeno);
		
	}
	protected function createComponentPacientForm()
	{
		$ids = $this->database->table('pojistovna');
		$idlist=[];
		$jmenolist=[];
		foreach ($ids as $id) {
			array_push($idlist,$id->id_pojistovna);
			array_push($jmenolist,$id->jmeno);
		}
		
		$resultlist=array_combine($idlist,$jmenolist);
		
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('rodne_cislo','*Rodné číslo:')->setRequired("Doplňte rodné číslo např. 9407035517");
		$form->addText('jmeno', '*Jméno:')->setRequired("Doplňte jméno.");
		$form->addText('prijmeni', '*Příjmení:')->setRequired("Doplňte přijmení.");
		$form->addText('pohlavi', 'Pohlaví:');
		$form->addText('adresa', 'Adresa:');
		$form->addSelect('id_pojistovna', 'ID pojišťovny:',$resultlist)->setPrompt('Zvolte pojišťovnu');
		
		$form->addSubmit('send', 'Přidat pacienta');
		$form->onSuccess[] = [$this, 'pacientFormSucceeded'];
		return $form;
	}
	public function pacientFormSucceeded($form, $values)
	{
		//$id_lek = $this->getParameter('id_lek');

		$this->database->table('pacient')->insert([
			'rodne_cislo' => $values->rodne_cislo,
			'jmeno' => $values->jmeno,
			'prijmeni' => $values->prijmeni,
			'pohlavi' => $values->pohlavi,
			'adresa' => $values->adresa,
			'id_pojistovna' => $values->id_pojistovna
		]);

		$this->flashMessage('Úspěšně vložen nový pacient', 'success');
		$this->redirect('this');
	}
	protected function createComponentPacientSearchForm()
	{
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('jmeno', 'Jméno:');
		$form->addText('prijmeni', 'Příjmení:');
		$form->addText('pohlavi', 'Pohlaví:');
		$form->addText('adresa', 'Adresa:');
		
		$form->addSubmit('send', 'Vyhledat pacienta');
		$form->onSuccess[] = [$this, 'pacientSearchFormSucceeded'];
		return $form;
	}
	public function pacientSearchFormSucceeded($form, $values)
	{
		$this->redirect('Pacienti:result', $values->adresa, $values->pohlavi, $values->prijmeni, $values->jmeno);
	}

	
	
}






















