<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Fixer\All;

use Symfony\CS\FixerInterface;
use Symfony\CS\Token;
use Symfony\CS\Tokens;

/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
class NewWithBracesFixer implements FixerInterface
{
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            $token = $tokens[$index];

            if (T_NEW !== $token->id) {
                continue;
            }

            $nextIndex = null;
            $nextToken = $tokens->getNextTokenOfKind($index, array(';', ',', '(', ')', '[', ']'), $nextIndex);

            // no correct end of code - break
            if (null === $nextToken) {
                break;
            }

            // entrance into array index syntax - need to look for exit
            if (!$nextToken->isArray() && '[' === $nextToken->content) {
                $braceLevel = 1;

                while (0 < $braceLevel) {
                    $nextToken = $tokens->getNextTokenOfKind($nextIndex, array('[', ']'), $nextIndex);
                    $braceLevel += ('[' === $nextToken->content ? 1 : -1);
                }

                $nextToken = $tokens[++$nextIndex];
            }

            // new statement with () - nothing to do
            if (!$nextToken->isArray() && '(' === $nextToken->content) {
                continue;
            }

            $meaningBeforeNextIndex = null;
            $tokens->getPrevNonWhitespace($nextIndex, array(), $meaningBeforeNextIndex);

            $tokens->insertAt($meaningBeforeNextIndex + 1, array(new Token('('), new Token(')')));
        }

        return $tokens->generateCode();
    }

    public function getLevel()
    {
        return FixerInterface::ALL_LEVEL;
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
        return 'new_with_braces';
    }

    public function getDescription()
    {
        return 'All instances created with new keyword must be followed by braces.';
    }
}
