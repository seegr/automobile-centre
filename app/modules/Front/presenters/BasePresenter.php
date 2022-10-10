<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\Breadcrumbs;
use App\FrontModule\Components\ChangeLocale;
use App\FrontModule\Components\ContactForm;
use App\Services\ParamService;
use App\Services\FileStorage;
use App\Services\LocaleService;
use App\FrontModule\Factories\BreadcrumbsFactory;
use App\FrontModule\Factories\ContactFormFactory;
use App\FrontModule\Factories\ChangeLocaleFactory;
use Nette\Utils\Strings;

// Base presenter for all frontend presenters

class BasePresenter extends \App\BaseModule\Presenters\BasePresenter
{
    private ParamService $paramService;
    private LocaleService $localeService;
    private FileStorage $fileStorage;
    private BreadcrumbsFactory $breadcrumbsFactory;
    private ContactFormFactory $contactFormFactory;
    private ChangeLocaleFactory $changeLocaleFactory;

    public function injectRepository(
        ParamService $paramService,
        LocaleService $localeService,
        FileStorage $fileStorage,
        BreadcrumbsFactory $breadcrumbsFactory,
        ContactFormFactory $contactFormFactory,
        ChangeLocaleFactory $changeLocaleFactory
    ) {
        $this->paramService = $paramService;
        $this->localeService = $localeService;
        $this->fileStorage = $fileStorage;
        $this->breadcrumbsFactory = $breadcrumbsFactory;
        $this->contactFormFactory = $contactFormFactory;
        $this->changeLocaleFactory = $changeLocaleFactory;
    }

    protected function beforeRender()
    {
        parent::beforeRender();
        $this->template->urlAbsolutePath = $this->getURL()->hostUrl;
        $this->template->urlFullDomain = $this->getURL()->host;
        $this->template->contact = $this->paramService->getContact();
        $keys = $this->paramService->getKeys();
        $this->template->keys = $keys;
        $this->template->recaptchaPublicKey = $keys->recaptcha['public'];
        $this->template->socialLinks = $this->paramService->getSocial();
        $this->template->navigation = $this->paramService->getNavigation();
    }

    ///////////////////// Create global components /////////////////////

    public function createComponentBreadcrumbs(): Breadcrumbs
    {
        return $this->breadcrumbsFactory->create();
    }

    public function createComponentContactForm(): ContactForm
    {
        return $this->contactFormFactory->create();
    }

    public function createComponentChangeLocale(): ChangeLocale
    {
        return $this->changeLocaleFactory->create();
    }

    ///////////////////// Helper functions /////////////////////

    public function translate(string $message, ...$args): string
    {
        return $this->localeService->translate($message, ...$args);
    }

    public function webalize(string $message, ...$args): string
    {
        return Strings::webalize($this->translate($message, ...$args));
    }

    public function image(string $id, ?string $alias = null): string
    {
        return $this->fileStorage->createAlias($id, $alias);
    }
    

    ///////////////////// YOUTUBE /////////////////////

    public function getYoutubeId(?string $url): string
    {
        if (!is_string($url)) {
            return '';
        }
        preg_match(
            "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",
            $url,
            $match
        );
        $videoId = print_r($match[1], true);
        return $videoId;
    }

    public function getYoutubeThumbnail(?string $url): string
    {
        if (!is_string($url)) {
            return '';
        }
        $id = $this->getYoutubeId($url);
        return "http://img.youtube.com/vi/$id/maxresdefault.jpg";
    }

    ///////////////////// VIMEO /////////////////////

    public function getVimeoId(?string $url): string
    {
        if (!is_string($url)) {
            return '';
        }
        preg_match(
            "/(?:http:|https:|)\/\/(?:player.|www.)?vimeo\.com\/(?:video\/|embed\/|watch\?\S*v=|v\/)?(\d*)/",
            $url,
            $match
        );
        $videoId = print_r($match[1], true);
        return $videoId;
    }

    public function getVimeoThumbnail(?string $url): string
    {
        if (!is_string($url)) {
            return '';
        }
        $response = json_decode(file_get_contents('http://vimeo.com/api/oembed.json?url=' . $url . '&width=1280'));

        return $response->thumbnail_url;
    }
}