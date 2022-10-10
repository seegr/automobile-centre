<?php

namespace App\Macros;

use Latte\MacroNode;
use Latte\Macros\BlockMacros;
use Latte\PhpWriter;

class Aliases extends BlockMacros
{

    public function initialize(): void
    {
        parent::initialize();
        $this->addMacro(
            'component',
            function (MacroNode $node, PhpWriter $writer) {
                $node->setArgs('file ' . $node->args);
                $this->macroEmbed($node, $writer);
            },
            [$this, 'macroEmbedEnd']
        );
        $this->addMacro(
            'elementx',
            function (MacroNode $node, PhpWriter $writer) {
                $this->macroInclude($node, $writer);
            }
        );
    }

}