<?php


namespace App\FrontModule\Factories;

use App\FrontModule\Components\Breadcrumbs;
use App\Services\LocaleService;
use App\Services\ParamService;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Application\LinkGenerator;

class BreadcrumbsFactory
{

    public function __construct(
        private ParamService $paramService,
        private LocaleService $localeService,
        private TemplateFactory $templateFactory,
        private LinkGenerator $linkGenerator,
    )
    {}

    public function create(): Breadcrumbs
    {
        return new Breadcrumbs(
            $this->paramService,
            $this->localeService,
            $this->templateFactory,
            $this->linkGenerator
        );
    }
}