<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Factories\DynamicFormFactory;
use App\Components\DynamicForm;
use App\Model\UserRepository;
use App\Services\MailService;
use Nette\Forms\Form;
use Nette\Security\AuthenticationException;
use Nette\Utils\Random;


final class SignPresenter extends \App\BaseModule\Presenters\BasePresenter
{
    public string $backlink = '';
    public string $token = '';
    private DynamicFormFactory $dynamicFormFactory;
    private UserRepository $userRepository;
    private MailService $mailService;

    public function __construct(
        DynamicFormFactory $dynamicFormFactory,
        UserRepository $userRepository,
        MailService $mailService
    )
    {
        parent::__construct();
        $this->dynamicFormFactory = $dynamicFormFactory;
        $this->userRepository = $userRepository;
        $this->mailService = $mailService;
    }

    public function actionRecovery(string $token)
    {
        $this->token = $token;
    }

    protected function createComponentSignInForm(): DynamicForm
    {
        return $this->dynamicFormFactory->create(
            function (DynamicForm $form) {
                $form->addText('username', 'Uživatelské jméno:')
                    ->setRequired('Uživatelské jméno je povinné');

                $form->addPassword('password', 'Heslo:')
                    ->setRequired('Heslo je povinné');

                $form->addCheckbox('remember', 'Zůstat přihlášený');
            },
            function (array $values, $skip, Form $form) {
                try {
                    $this->user->setExpiration(isset($values['remember']) && $values['remember'] ? '14 days' : '20 minutes');
                    $this->user->login($values['username'], $values['password']);
                } catch (AuthenticationException $e) {
                    $form->addError('Uživatelské údaje jsou nesprávné');
                    return;
                }
                $this->restoreRequest($this->backlink);
                $this->redirect('Dashboard:');
            },
            ["", "Přihlásit se"],
            null,
            true
        );
    }

    protected function createComponentRecoveryForm(): DynamicForm
    {
        return $this->dynamicFormFactory->create(
            function (DynamicForm $form) {
                $password = $form->addPassword('password', 'Heslo')
                    ->addRule(Form::MIN_LENGTH, 'Heslo musí mít minimálně %d znaků', 6)
                    ->setRequired('Heslo je povinné');

                $form->addPassword('passwordVerification', 'Heslo pro kontrolu')
                    ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                    ->addRule(Form::EQUAL, 'Hesla se neshodují', $password);
            },
            function (array $values, $skip, Form $form) {
                $row = $this->userRepository->findAll()->where('token', $this->token);
                $user = $row->fetch();
                if (!$user) {
                    $form->addError('Vypršela platnost odkazu pro vytvoření hesla.');
                    return;
                } else {
                    $row->update(
                        [
                            'token' => null,
                            'password' => $this->passwords->hash($values['password'])
                        ]
                    );
                    $this->user->login($user->email, $values['password']);
                    $this->redirect('Dashboard:');
                }
            },
            ["", "Vytvořit heslo"],
            null,
            true
        );
    }

    protected function createComponentForgottenPasswordForm(): DynamicForm
    {
        return $this->dynamicFormFactory->create(
            function (DynamicForm $form) {
                $form->addEmail('email', 'Email')
                    ->setRequired('Email je povinný');
            },
            function (array $values) {
                $row = $this->userRepository->findAll()->where('email', $values['email']);
                $user = $row->fetch();
                if ($user) {
                    $token = Random::generate(64);
                    $row->update(['token' => $token]);
                    $this->mailService->sendUserNewPassword($values['email'], $token);
                }
                $this->flashMessage('Na uvedený email byl zaslán odkaz pro reset hesla');

            },
            ["", "Resetovat heslo"],
            null,
            true
        );
    }

    public function actionOut()
    {
        $this->getUser()->logout();
    }

}
