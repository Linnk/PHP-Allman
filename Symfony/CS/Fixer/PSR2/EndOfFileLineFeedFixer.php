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

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class EndOfFileLineFeedFixer implements FixerInterface
{
    public function fix(\SplFileInfo $file, $content)
    {
        // [Structure] A file must always end with a linefeed character

        $content = rtrim($content);

        if (strlen($content)) {
            return $content."\n";
        }

        return $content;
    }

    public function getLevel()
    {
        return FixerInterface::PSR2_LEVEL;
    }

    public function getPriority()
    {
        // must run last to be sure the file is properly formatted before it runs
        return -50;
    }

    public function supports(\SplFileInfo $file)
    {
        return true;
    }

    public function getName()
    {
        return 'eof_ending';
    }

    public function getDescription()
    {
        return 'A file must always end with an empty line feed.';
    }
}
