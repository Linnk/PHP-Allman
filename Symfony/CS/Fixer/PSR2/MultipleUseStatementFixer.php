<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Fixer\PSR2;

use Symfony\CS\FixerInterface;
use Symfony\CS\Tokens;

/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
class MultipleUseStatementFixer implements FixerInterface
{
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);
        $uses = array_reverse($tokens->getNamespaceUseIndexes());

        foreach ($uses as $index) {
            $endIndex = null;
            $tokens->getNextTokenOfKind($index, array(';'), $endIndex);
            $declarationContent = $tokens->generatePartialCode($index + 1, $endIndex - 1);

            $declarationParts = explode(',', $declarationContent);

            if (1 === count($declarationParts)) {
                continue;
            }

            $declarationContent = array();

            foreach ($declarationParts as $declarationPart) {
                $declarationContent[] = 'use '.trim($declarationPart).';';
            }

            $declarationContent = implode("\n".$this->detectIndent($tokens, $index), $declarationContent);

            for ($i = $index; $i <= $endIndex; ++$i) {
                $tokens[$i]->clear();
            }

            $declarationTokens = Tokens::fromCode('<?php '.$declarationContent);
            $declarationTokens[0]->clear();

            $tokens->insertAt($index, $declarationTokens);
        }

        return $tokens->generateCode();
    }

    private function detectIndent(Tokens $tokens, $index)
    {
        $prevIndex = $index - 1;
        $prevToken = $tokens[$prevIndex];

        // if can not detect indent:
        if (!$prevToken->isWhitespace()) {
            return '';
        }

        $explodedContent = explode("\n", $prevToken->content);

        return end($explodedContent);
    }

    public function getLevel()
    {
        // defined in PSR2 ¶3
        return FixerInterface::PSR2_LEVEL;
    }

    public function getPriority()
    {
        return 0;
    }

    public function supports(\SplFileInfo $file)
    {
        return true;
    }

    public function getName()
    {
        return 'multiple_use';
    }

    public function getDescription()
    {
        return 'There MUST be one use keyword per declaration.';
    }
}
