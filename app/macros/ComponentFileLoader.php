<?php declare(strict_types = 1);

namespace App\Macros;

use Latte\Loaders\FileLoader as FileLoaderLatte;

class ComponentFileLoader extends FileLoaderLatte
{
    private static function isComponent(string $file): bool {
        $firstLetter = substr($file, 0, 1);
        return in_array(
            $firstLetter,
            ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']
        );
    }

    public function getContent($fileName): string
    {
        if (self::isComponent($fileName)) {
            return parent::getContent(COMP_DIR . $fileName . "/" . $fileName . ".latte");
        }
        return parent::getContent($fileName);
    }

    public function getReferredName($file, $referringFile): string
    {
        if (self::isComponent($file)) {
            return $file;
        }

        return parent::getReferredName($file, $referringFile);
    }

}