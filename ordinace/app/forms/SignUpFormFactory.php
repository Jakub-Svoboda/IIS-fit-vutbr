<?php
/*
Source: https://github.com/nette/sandbox
*/
namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;


class SignUpFormFactory
{
	use Nette\SmartObject;

	const PASSWORD_MIN_LENGTH = 7;

	/** @var FormFactory */
	private $factory;

	/** @var Model\UserManager */
	private $userManager;


	public function __construct(FormFactory $factory, Model\UserManager $userManager)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$roles = [
			'doctor' => 'doctor',
			'sestra' => 'sestra',
		];
		
		$form = $this->factory->create();
		$form->addText('username', '*Username:')->setRequired('Zvolte username.');
		$form->addRadioList('role', '*Role:', $roles)->setRequired('Zvolte roli');
		$form->addPassword('password', '*Heslo:')
			->setOption('description', sprintf('nejméně %d znaků', self::PASSWORD_MIN_LENGTH))
			->setRequired('Zvolte heslo.')
			->addRule($form::MIN_LENGTH, null, self::PASSWORD_MIN_LENGTH);

		$form->addSubmit('send', 'Přidat uživatele');

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->userManager->add($values->username, $values->role, $values->password);
			} catch (Model\DuplicateNameException $e) {
				$form['username']->addError('Uživatel s tímto jménem již existuje.');
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
