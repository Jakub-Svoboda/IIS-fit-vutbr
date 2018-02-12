<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class PacientPresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShow($id_pacient)
    {
        $leky=[];
        $pacient = $this->database->table('pacient')->get($id_pacient);
		if (!$pacient) {
			$this->error('Stránka nebyla nalezena');
		}

		$this->template->pojistovna=$this->database->table('pojistovna')->get($pacient->id_pojistovna);
		$this->template->pacient = $pacient;
		$this->template->lecby = $this->database->table('lecba')->where('id_pacient = ?', $pacient->id_pacient);
		$this->template->vysetrenis = $this->database->table('vysetreni')->where('id_pacient = ?', $pacient->id_pacient);
		$this->template->osetrenis = $this->database->table('osetreni')->where('id_pacient = ?', $pacient->id_pacient);

        foreach ($this->template->lecby as $lek){
            array_push($leky,$this->database->table('lek')->get($lek->id_lek)/*->where('id_lek = ?', $lek->id_lek)*/);
        }
        $this->template->leky = $leky;
	}
	public function renderEdit($id_pacient)
    {
        $pacient = $this->database->table('pacient')->get($id_pacient);
		if (!$pacient) {
			$this->error('Stránka nebyla nalezena');
		}
		$this->template->pacient = $pacient;
	}
	protected function createComponentLecbaForm()
	{
		$ids = $this->database->table('lek');
		$idlist=[];
		$nazevlist=[];
		foreach ($ids as $id) {
			array_push($idlist,$id->id_lek);
			array_push($nazevlist,$id->nazev);
		}
		$resultlist=array_combine($idlist,$nazevlist);
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('pocet_dni_uzivani','Počet dní užívání:');
		$form->addText('mnozstvi', 'Množství:');
		$form->addSelect('id_lek', '*Lék:',$resultlist)->setRequired("Doplňte lék.")->setPrompt('Zvolte lék');
		$form->addSubmit('send', 'Přidat předpis');
		$form->onSuccess[] = [$this, 'lecbaFormSucceeded'];
		return $form;
	}
	public function lecbaFormSucceeded($form, $values)
	{
		$id_pacient = $this->getParameter('id_pacient');
		
		$this->database->table('lecba')->insert([
			'pocet_dni_uzivani' => $values->pocet_dni_uzivani,
			'mnozstvi' => $values->mnozstvi,
			'id_lek' => $values->id_lek,
			'id_pacient' => $id_pacient
		]);

		$this->flashMessage('Úspěšně vložen nový předpis', 'success');
		$this->redirect('this');
	}
	protected function createComponentOsetreniDeleteForm($id_osetreni){
		
		$form = new Form; // means Nette\Application\UI\Form
		$form->addSubmit('send', 'Odstranit');
		$form->onSuccess[] = [$this, 'osetreniDeleteFormSucceeded'];
		return $form;
	}
	public function osetreniDeleteFormSucceeded($form, $values)
	{	
		$id_osetreni = $this->getParameter('id_osetreni');
		$this->database->table('osetreni')->where('id_osetreni = ?', $id_osetreni)->delete();
		$this->redirect('Osetrenis:show');
		return $values;
	}
	protected function createComponentOsetreniForm()
	{
		$form = new Form; // means Nette\Application\UI\Form

		$form->addText('datum', 'Datum (YYYY-MM-DD):')->setDefaultValue(date("Y-m-d"))->addRule($form::PATTERN, 'Format must be YYYY-MM-DD', '[0-9]{4}-[0-9]{2}-[0-9]{2}')->setRequired(FALSE);
		$form->addTextArea('popis', 'Popis:');
		
		$form->addSubmit('send', 'Přidat ošetření');
		$form->onSuccess[] = [$this, 'osetreniFormSucceeded'];
		return $form;
	}
	public function osetreniFormSucceeded($form, $values)
	{
		$id_pacient = $this->getParameter('id_pacient');

		$this->database->table('osetreni')->insert([
			'datum' => $values->datum,
			'popis' => $values->popis,
			'id_pacient' => $id_pacient
		]);

		$this->flashMessage('Úspěšně vloženo nové ošetření', 'success');
		$this->redirect('this');
	}	
	protected function createComponentVysetreniForm()
	{	
		$ids = $this->database->table('vykon');
		$nazevlist=[];
		foreach ($ids as $id) {
			array_push($nazevlist,$id->nazev);
		}
		
		$form = new Form; // means Nette\Application\UI\Form
		
		$form->addSelect('nazev', '*Název:',$nazevlist)->setItems($nazevlist, false)->setRequired("Doplňte název vyšetření.")->setPrompt('Zvolte vyšetření');
		$form->addSubmit('send', 'Přidat vyšetření');
		$form->onSuccess[] = [$this, 'vysetreniFormSucceeded'];
		return $form;
	}
	public function vysetreniFormSucceeded($form, $values)
	{
		$id_pacient = $this->getParameter('id_pacient');
		$this->database->table('vysetreni')->insert([
			'nazev' => $values->nazev,
			'id_pacient' => $id_pacient
		]);

		$this->flashMessage('Úspěšně vloženo nové vyšetření', 'success');
		$this->redirect('this');
	}
	protected function createComponentPacientEditForm()
	{
		$form = new Form; // means Nette\Application\UI\Form
		
		
		$pacId =  $this->getParameter('id_pacient');
		$pac = $this->database->table('pacient')->get($pacId);

		$form->addText('jmeno', 'Jméno:')->setRequired()->setDefaultValue($pac->jmeno);
		$form->addText('prijmeni', 'Příjmení:')->setRequired()->setDefaultValue($pac->prijmeni);
		$form->addText('adresa', 'Adresa:')->setRequired()->setDefaultValue($pac->adresa);
		
		$form->addSubmit('send', 'Upravit kartu pacienta');
		$form->onSuccess[] = [$this, 'pacientEditFormSucceeded'];
		return $form;
		
	}
	public function pacientEditFormSucceeded($form, $values)
	{
		$pacId =  $this->getParameter('id_pacient');
		$pac = $this->database->table('pacient')->get($pacId);
		$pac->update($values);

		$this->flashMessage('Pacient upraven', 'success');
		$this->redirect('Pacienti:show');
	}
}
































