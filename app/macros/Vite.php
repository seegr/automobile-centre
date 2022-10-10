<?php

// https://github.com/andrefelipe/vite-php-setup/blob/master/public/helpers.php

namespace App\Macros;

class Vite
{
	private string $entry;
	private string $baseProdPath;
	private string $baseDevPath;
	private bool $isDevelopment;

	private const PORT_FRONT = 3303;
	private const PORT_ADMIN = 3304;

	public function __construct(
		string $entry,
		string $module
	) {
		$this->entry = $entry;
		$this->baseProdPath = '/dist/' . $module . '/';
		$port = $module === 'front' ? self::PORT_FRONT : self::PORT_ADMIN;
		$this->baseDevPath = 'http://localhost:' . $port . '/';
		$this->isDevelopment = $this->isDevServerRunning();
	}

	private function isDevServerRunning(): bool {
		$handle = curl_init($this->baseDevPath . $this->entry);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_NOBODY, true);
		curl_exec($handle);
		$error = curl_errno($handle);
		curl_close($handle);
		return $error === CURLE_OK;
	}

	public function __toString(): string
	{
		return
			$this->jsTag() .
			$this->cssTag();
//            $this->jsPreloadImports();

	}

	public function cssTag(): string
	{
		if ($this->isDevelopment) {
			return '';
		}

		$tags = '';
		foreach ($this->getUrls("css") as $url) {
			$tags .= '<link rel="stylesheet" href="' . $url . '">';
		}
		return $tags;
	}

	public function jsPreloadImports(): string
	{
		if ($this->isDevelopment) {
			return '';
		}

		$res = '';
		foreach ($this->getUrls("imports") as $url) {
			$res .= '<link rel="modulepreload" href="'
				. $url
				. '">';
		}
		return $res;
	}

	private function jsTag()
	{
		$url = $this->isDevelopment
			? $this->baseDevPath . $this->entry
			: $this->assetUrl();

		if (!$url) {
			return '';
		}

		$script = '<script type="module" crossorigin src="'
			. $url
			. '"></script>';

		return ($this->isDevelopment ?
				'<script type="module" src="' . $this->baseDevPath . '@vite/client"></script>' :
				'') . $script;
	}

	private function assetUrl(): string {
		$manifest = $this->getManifest();
		return isset($manifest[$this->entry])
			? $this->baseProdPath . $manifest[$this->entry]['file']
			: '';
	}

	private function getManifest(): array
	{
		$content = file_get_contents(WWW_DIR . $this->baseProdPath . 'manifest.json');;
		return json_decode($content, true);
	}

	private function getUrls(string $key): array
	{
		$urls = [];
		$manifest = $this->getManifest();

		if (!empty($manifest[$this->entry][$key])) {
			foreach ($manifest[$this->entry][$key] as $url) {
				$urls[] = $this->baseProdPath . $url;
			}
		}
		return $urls;
	}


}