<?php

namespace App\Presenters;
use Nette\Application\UI\Form;
use Nette\Security\User;

use Nette;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
	public $database;
	public $user;
	
	public function __construct(Nette\Database\Context $database, \Nette\Security\User $user)
    {
		$this->database = $database;
		parent::__construct();
        $this->user = $user;
		$user->setExpiration('60 minutes');
		
    }
	public function renderDefault()
	{
		
		
	}
}
