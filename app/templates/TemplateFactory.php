<?php

namespace App\Templates;

use App\Filter\FileFilter;
use App\Types\Orientation;
use App\Types\Severity;
use App\Types\Size;
use App\Types\Variant;
use Nette\Application\UI\Control;
use Nette\Application\UI\Template;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\Caching\Storage;
use Nette\Http\Request as HttpRequest;
use Nette\Security\User;

class TemplateFactory extends \Nette\Bridges\ApplicationLatte\TemplateFactory
{
    private FileFilter $fileFilter;

    public function __construct(
        FileFilter $fileFilter,
        LatteFactory $latteFactory,
        HttpRequest $httpRequest = null,
        User $user = null,
        Storage $cacheStorage = null
    ) {
        parent::__construct($latteFactory, $httpRequest, $user, $cacheStorage);
        $this->fileFilter = $fileFilter;
    }

    public function createTemplate(Control $control = null, string $class = null): Template
    {
        $template = parent::createTemplate($control);
        $template->addFilter(null, 'App\Filter\StaticFilters::common');
        $template->addFilter('image', fn(...$args) => $this->fileFilter->image(...$args));
        $template->addFilter('srcset', fn(...$args) => $this->fileFilter->srcset(...$args));
        $template->addFilter('file', fn(...$args) => $this->fileFilter->file(...$args));
        $template->addFilter('width', fn(...$args) => $this->fileFilter->width(...$args));
        $template->addFilter('height', fn(...$args) => $this->fileFilter->height(...$args));

        $template->Size = Size::class;
        $template->Variant = Variant::class;
        $template->Severity = Severity::class;
        $template->Orientation = Orientation::class;

        return $template;
    }
}