<?php


namespace App\FrontModule\Factories;

use App\FrontModule\Components\ContactForm;
use App\Services\LocaleService;
use App\Services\ParamService;
use App\Services\RecaptchaService;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Application\LinkGenerator;

class ContactFormFactory
{
    public function __construct(
        private ParamService $paramService,
        private LocaleService $localeService,
        private RecaptchaService $recaptchaService,
        private TemplateFactory $templateFactory,
        private LinkGenerator $linkGenerator,
    )
    {}

    public function create(): ContactForm
    {
        return new ContactForm(
            $this->paramService,
            $this->localeService,
            $this->recaptchaService,
            $this->templateFactory,
            $this->linkGenerator,
        );
    }
}