<?php

declare(strict_types=1);

namespace App\FrontModule\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Utils\ArrayHash;
use App\Services\LocaleService;
use App\Services\ParamService;
use App\Services\RecaptchaService;
use Tracy\Debugger;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Application\LinkGenerator;

class ContactForm extends Control {

    public $onSuccess;
    public $onError;

    public function __construct(
        private ParamService $paramService,
        private LocaleService $localeService,
        private RecaptchaService $recaptchaService,
        private TemplateFactory $templateFactory,
        private LinkGenerator $linkGenerator,
    )
    {}

    public function render(): void
    {
        $this->template->setFile(__DIR__ . '/ContactForm.latte');
        $this->template->render();
    }

    public function createComponentForm(): Form
    {
        $gdprConsent = $this->presenter->getHttpRequest()->getUrl()->getBasePath() . 'downloads/gdpr_file.pdf';

        $form = new Form;

        $form->addText('fullname', $this->localeService->translate('form.field.fullname'))
            ->setRequired($this->localeService->translate('form.message.required', ['requiredItem' => '"%label"']));

        $form->addText('phone', $this->localeService->translate('form.field.phone'));

        $form->addEmail('email', $this->localeService->translate('form.field.email'))
            ->addRule(Form::EMAIL, $this->localeService->translate('form.message.invalidMail'))
            ->setRequired($this->localeService->translate('form.message.required', ['requiredItem' => '"%label"']));

        $form->addTextarea('message', $this->localeService->translate('form.field.message'))
            ->setRequired($this->localeService->translate('form.message.required', ['requiredItem' => '"%label"']));

        $form->addCheckbox('gdprConsent', $this->localeService->translate('form.field.gdprConsent', ['link' => $gdprConsent]))
            ->setRequired($this->localeService->translate('form.message.requiredGdprConsent'));

        $form->addCheckbox('newsletterConsent', $this->localeService->translate('form.field.newsletterConsent', ['link' => $gdprConsent]))
            ->setRequired($this->localeService->translate('form.message.requiredNewsletterConsent'));

        $form->addHidden('recaptcha_token');
        
        $form->addSubmit('send', $this->localeService->translate('form.field.submit'));

        $form->onSuccess[] = [$this, 'processForm'];
        
        return $form;
    }

    public function processForm(Form $form, ArrayHash $values): void
    {
        $paramContact = $this->paramService->getContact();
        $recaptcha = $this->recaptchaService->verifyRecaptcha($values->recaptcha_token);

        if ($recaptcha) {
            $mailer = new SendmailMailer;

            // Prepare Template object
            $template = $this->templateFactory->createTemplate();
            // Enable translations
            $template->getLatte()->addFilter('translate', [$this->localeService, 'translate']);
            // Add link generator
            $template->getLatte()->addProvider('uiControl', $this->linkGenerator);
            
            // Mail to admin
            $mailAdmin = new Message;
            $mailAdmin->setFrom($paramContact->email);
            $mailAdmin->setSubject('Nová zpráva - ' . $values->fullname);
            $mailAdmin->setHtmlBody($template->renderToString(__DIR__ . '/ContactFormAdminMail.latte', ['values' => $values]));
            $mailAdmin->addTo($paramContact->email);
            
            // Mail to user
            $mailUser = new Message;
            $mailUser->setFrom($paramContact->email);
            $mailUser->addReplyTo($paramContact->email);
            $mailUser->setSubject($this->localeService->translate('ContactForm.userMailSubject'));
            $mailUser->setHtmlBody($template->renderToString(__DIR__ . '/ContactFormUserMail.latte', ['values' => $values]));
            $mailUser->addTo($values->email);

            // Save data to database
            // try {
            //     $this->formRepository->saveContactForm($values);
            // } catch (\Exception $e) {
            //     $this->presenter->flashMessage($this->localeService->translate('form.message.sendFailed'), 'error');
            //     Debugger::log(new \Exception($e->getMessage()));
            // }

            // Send emails
            try {
                $mailer->send($mailAdmin);
                $mailer->send($mailUser);
                $this->presenter->flashMessage($this->localeService->translate('form.message.sendSuccess'), 'success');
            } catch (\Exception $e) {
                $this->presenter->flashMessage($this->localeService->translate('form.message.sendFailed'), 'error');
                Debugger::log(new \Exception($e->getMessage()));
            }
        } else {
            $this->presenter->flashMessage($this->localeService->translate('form.message.errorYouAreRobot'), 'error');
        }
        $this->redirect('this');
    }
}