<?php

namespace App\Macros;

use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

abstract class CustomMacros extends MacroSet {


    public static function install(Compiler $compiler)
    {
        $set = new MacroSet($compiler);

        $set->addMacro(
            'vite',
            function (MacroNode $node) {
                list($assetName, $moduleName) = explode(' ', $node->args);
                $vite = new Vite($assetName, $moduleName);
                return "echo '$vite';";
            }
        );

	    $set->addMacro(
		    'element',
		    function (MacroNode $node, PhpWriter $writer) {
			    return $writer->write(
				    '$this->createTemplate(COMP_DIR . %node.word . "/" . %node.word . ".latte", %node.array? + $this->params + ["parent" => false], "include")->renderToContentType(%raw);',
				    $writer->write(
					    'function ($s, $type) { $_fi = new LR\FilterInfo($type); return %modifyContent($s); }'
				    )
			    );
		    }
	    );
    }
}