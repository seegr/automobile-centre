<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Model\ArticleRepository;
use App\Model\FileRepository;
use Nette\Application\UI\Form;

final class HomepagePresenter extends BasePresenter
{

	const SECTION_UVOD = 'uvod';
	const SECTION_UZAVERKY = 'uzaverky';
	const SECTION_AKCIONARI = 'akcionari';
	const SECTION_KONTAKT = 'kontakt';

	const PAGE_SECTIONS = [
		self::SECTION_UZAVERKY,
		self::SECTION_AKCIONARI,
		self::SECTION_KONTAKT
	];

	const NAV = [
		self::SECTION_UVOD,
		self::SECTION_UZAVERKY,
		self::SECTION_AKCIONARI,
		self::SECTION_KONTAKT
	];

	const ROZVAHA_ID = 'rozvaha';
	const VZAZ_ID = 'vzaz';
	const ROZVAHA_PREFIX = 'ac_cr_rozvaha_';
	const VZAZ_PREFIX = 'ac_cr_vzaz_';

	const VYPIS = 'vypis';
	const VYPIS_FILE = 'vypis-904113.pdf';


	public function __construct(
	)
	{}

	public function renderDefault()
	{
		$this->template->filesDir = __DIR__ . '/../../../../www/files/';
	}

	public function createComponentLoginForm(): Form
	{
		$form = new Form();

		$form->addTextArea('user', 'Uživatelské jméno')->setRequired();
		$form->addPassword('pass', 'Heslo')->setRequired();
		$form->addSubmit('submit', 'Přihlásit');

//		$form->onSubmit[] = function() {
//			$this->flashMessage('Nesprávné přihlašovací údaje', 'warning');
//		};

		return $form;
	}

	public function handleLogin()
	{
		$this->flashMessage('Nesprávné přihlašovací údaje', 'warning');
		$this->redrawControl('flashes');
	}
}
