<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class FakturyPresenter extends Nette\Application\UI\Presenter
{
	public $database;
	public function __construct(Nette\Database\Context $database)
    {
		$this->database = $database;
		
    }
	public function renderShow()
	{		
		
	}
	public function renderResult($datum, $castka, $pojistovna, $pacient)
	{	if($datum==NULL and $castka==NULL and $pojistovna==NULL and $pacient==null)$this->template->faktury=$this->database->table('faktura');
		if($datum==NULL and $castka==NULL and $pojistovna==NULL and $pacient!=null)$this->template->faktury=$this->database->table('faktura')->where('id_pacient = ?', $pacient);
		if($datum==NULL and $castka==NULL and $pojistovna!=NULL and $pacient==null)$this->template->faktury=$this->database->table('faktura')->where('id_pojistovna = ?', $pojistovna);
		if($datum==NULL and $castka==NULL and $pojistovna!=NULL and $pacient!=null)$this->template->faktury=$this->database->table('faktura')->where('id_pojistovna = ? AND id_pacient = ?', $pojistovna, $pacient);
		if($datum==NULL and $castka!=NULL and $pojistovna==NULL and $pacient==null)$this->template->faktury=$this->database->table('faktura')->where('castka = ?', $castka);	
		if($datum==NULL and $castka!=NULL and $pojistovna==NULL and $pacient!=null)$this->template->faktury=$this->database->table('faktura')->where('id_pacient = ? AND castka = ?', $pacient, $castka);
		if($datum==NULL and $castka!=NULL and $pojistovna!=NULL and $pacient==null)$this->template->faktury=$this->database->table('faktura')->where('castka = ? AND id_pojistovna = ?', $castka, $pojistovna);
		if($datum==NULL and $castka!=NULL and $pojistovna!=NULL and $pacient!=null)$this->template->faktury=$this->database->table('faktura')->where('castka = ? AND id_pojistovna = ? AND id_pacient', $castka, $pojistovna, $pacient);
		if($datum!=NULL and $castka==NULL and $pojistovna==NULL and $pacient==null)$this->template->faktury=$this->database->table('faktura')->where('datum = ?', $datum);
		if($datum!=NULL and $castka==NULL and $pojistovna==NULL and $pacient!=null)$this->template->faktury=$this->database->table('faktura')->where('datum = ? AND id_pacient = ?', $datum, $pacient);
		if($datum!=NULL and $castka==NULL and $pojistovna!=NULL and $pacient==null)$this->template->faktury=$this->database->table('faktura')->where('datum = ? AND id_pojistovna = ?', $datum, $pojistovna);
		if($datum!=NULL and $castka==NULL and $pojistovna!=NULL and $pacient!=null)$this->template->faktury=$this->database->table('faktura')->where('datum = ? AND id_pojistovna = ? AND id_pacient = ?', $datum, $pojistovna, $pacient);
		if($datum!=NULL and $castka!=NULL and $pojistovna==NULL and $pacient==null)$this->template->faktury=$this->database->table('faktura')->where('datum = ? AND castka = ?', $datum, $castka);	
		if($datum!=NULL and $castka!=NULL and $pojistovna==NULL and $pacient!=null)$this->template->faktury=$this->database->table('faktura')->where('datum = ? AND id_pacient = ? AND castka = ?', $datum, $pacient, $castka);
		if($datum!=NULL and $castka!=NULL and $pojistovna!=NULL and $pacient==null)$this->template->faktury=$this->database->table('faktura')->where('datum = ? AND castka = ? AND id_pojistovna = ?', $datum, $castka, $pojistovna);
		if($datum!=NULL and $castka!=NULL and $pojistovna!=NULL and $pacient!=null)$this->template->faktury=$this->database->table('faktura')->where('datum = ? AND castka = ? AND id_pojistovna = ? AND id_pacient = ?', $datum, $castka, $pojistovna, $pacient);
		
	}
	protected function createComponentFakturaForm()
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
		$resultlist = array_combine($idlist,$jmenolist);
		
		$form = new Form; // means Nette\Application\UI\Form

		$ids2 = $this->database->table('pojistovna');
		$pojistovnyList=[];
		foreach ($ids2 as $id2){
			array_push($pojistovnyList, $id2->jmeno);
		}

		$form->addText('datum', 'Datum (YYYY-MM-DD):')->setDefaultValue(date("Y-m-d"))->addRule($form::PATTERN, 'Format must be YYYY-MM-DD', '[0-9]{4}-[0-9]{2}-[0-9]{2}')->setRequired(FALSE);
		$form->addInteger('castka', '*Částka:')->setRequired("Doplňte částku.");
		$form->addSelect('id_pojistovna', 'ID pojišťovny:', $pojistovnyList)->setPrompt('Zvolte pojišťovnu');
		$form->addSelect('id_pacient', '*Pacient:',$resultlist)->setRequired("Doplňte ID pacienta")->setPrompt('Zvolte pacienta');
		
		$form->addSubmit('send', 'Přidat fakturu');
		$form->onSuccess[] = [$this, 'fakturaFormSucceeded'];
		return $form;
	}
	public function fakturaFormSucceeded($form, $values)
	{
		//$id_lek = $this->getParameter('id_lek');

		$this->database->table('faktura')->insert([
			'datum' => $values->datum,
			'castka' => $values->castka,
			'id_pojistovna' => $values->id_pojistovna,
			'id_pacient' => $values->id_pacient
		]);

		$this->flashMessage('Úspěšně vložena nová faktura', 'success');
		$this->redirect('this');
	}
	protected function createComponentFakturaSearchForm()
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

        $ids2 = $this->database->table('pojistovna');
		$nazevPoj=[];
        foreach ($ids2 as $id) {
            array_push($nazevPoj,$id->jmeno);
        }

		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('datum', 'Datum (YYYY-MM-DD):')->setDefaultValue(date("Y-m-d"))->addRule($form::PATTERN, 'Format must be YYYY-MM-DD', '[0-9]{4}-[0-9]{2}-[0-9]{2}')->setRequired(FALSE);
		$form->addText('castka', 'Částka:');
		$form->addSelect('id_pojistovna', 'Pojišťovna:',$nazevPoj)->setPrompt('Zvolte pojišťovnu');
		$form->addSelect('id_pacient', 'Pacient:',$resultlist)->setPrompt('Zvolte pacienta');
		
		$form->addSubmit('send', 'Vyhledat fakturu');
		$form->onSuccess[] = [$this, 'fakturaSearchFormSucceeded'];
		return $form;
	}
	public function fakturaSearchFormSucceeded($form, $values)
	{
		$this->redirect('Faktury:result', $values->datum, $values->castka, $values->id_pojistovna, $values->id_pacient);
		return $values;
	}
	
}