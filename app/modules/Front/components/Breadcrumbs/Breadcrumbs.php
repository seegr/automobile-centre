<?php

declare(strict_types=1);

namespace App\FrontModule\Components;

use Nette\Application\UI\Control;
use App\Services\LocaleService;
use App\Services\ParamService;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Application\LinkGenerator;

class Breadcrumbs extends Control {

    public function __construct(
        private ParamService $paramService,
        private LocaleService $localeService,
        private TemplateFactory $templateFactory,
        private LinkGenerator $linkGenerator,
    )
    {}

    public function render(): void
    {
        $this->template->breadcrumbs = [
            0 => (object) [
                'linkUrl' => $this->linkGenerator->link('Front:Homepage:default'),
                'linkText' => 'Homepage',
            ],
        ];
        $this->template->setFile(__DIR__ . '/Breadcrumbs.latte');
        $this->template->render();
    }
}