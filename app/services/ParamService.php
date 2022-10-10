<?php


namespace App\Services;

class ParamService
{
    public function __construct(
        private array $contact,
        private array $social,
        private array $links,
        private array $keys,
	    private array $navigation
    )
    { }

    public function getContact(): object
    {
        return (object) $this->contact;
    }

    public function getSocial(): object
    {
        return (object) $this->social;
    }

    public function getLinks(): object
    {
        return (object) $this->links;
    }

    public function getLink(string $key): string
    {
        return $this->links[$key];
    }

    public function getKeys(): object
    {
        return (object) $this->keys;
    }

	public function getNavigation(): object
	{
		return (object) $this->navigation;
	}
}