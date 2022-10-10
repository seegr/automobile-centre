<?php

namespace App\FrontModule\Components;

use App\Services\LocaleService;
use Nette\Application\UI\Control;

class ChangeLocale extends Control
{
    public function __construct(
        private LocaleService $localeService
    ){}

    public function render(): void
    {
        $this->template->currentLocale = $this->localeService->getLocale();
        $this->template->localeList = $this->localeService->getLocalesTranslation();
        $this->template->setFile(__DIR__ . '/ChangeLocale.latte');
        $this->template->render();
    }

    public function handleChangeLocale(string $locale): void
    {
        $this->getPresenter()->redirect('Homepage:default', ['locale' => $locale]);
    }
}