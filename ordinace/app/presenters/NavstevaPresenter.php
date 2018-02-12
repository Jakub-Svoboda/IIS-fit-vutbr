<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

class NavstevaPresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShow($id_navsteva)
    {
        $navsteva = $this->database->table('navsteva')->get($id_navsteva);
		if (!$navsteva) {
			$this->error('Stránka nebyla nalezena');
		}

		$this->template->navsteva = $navsteva;
        $this->template->pacient = $this->database->table('pacient')->get($navsteva->id_pacient);
	}
	protected function createComponentNavstevaDeleteForm($id_navsteva){
		
		$form = new Form; // means Nette\Application\UI\Form
		$form->addSubmit('send', 'Odstranit');
		$form->onSuccess[] = [$this, 'navstevaDeleteFormSucceeded'];
		return $form;
	}
	public function navstevaDeleteFormSucceeded($form, $values)
	{	
		$id_navsteva = $this->getParameter('id_navsteva');
		$this->database->table('navsteva')->where('id_navsteva = ?', $id_navsteva)->delete();
		$this->flashMessage('Návštěva byla úspěšně odstraněna.', 'success');
		$this->redirect('Navstevy:show');
		return $values;
	}	
}