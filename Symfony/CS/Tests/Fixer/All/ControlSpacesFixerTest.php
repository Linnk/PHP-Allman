<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Tests\Fixer\All;

use Symfony\CS\Fixer\All\ControlSpacesFixer as Fixer;

class ControlSpacesFixerTest extends \PHPUnit_Framework_TestCase
{
    public function testFixControlsWithSuffixBrace()
    {
        $fixer = new Fixer();

        $try = 'try{';
        $tryFixed = 'try {';
        $this->assertSame($tryFixed, $fixer->fix($this->getTestFile(), $try));
        $this->assertSame($tryFixed, $fixer->fix($this->getTestFile(), $tryFixed));
    }

    public function testFixControlsWithPrefixBraceAndParentheses()
    {
        $fixer = new Fixer();

        $while = 'do { ... }while($test);';
        $whileFixed = 'do { ... } while ($test);';
        $this->assertSame($whileFixed, $fixer->fix($this->getTestFile(), $while));
        $this->assertSame($whileFixed, $fixer->fix($this->getTestFile(), $whileFixed));
    }

    /**
     * @dataProvider testFixControlsWithParenthesesAndSuffixBraceProvider
     */
    public function testFixControlsWithParenthesesAndSuffixBrace($if, $ifFixed)
    {
        $fixer = new Fixer();

        $this->assertSame($ifFixed, $fixer->fix($this->getTestFile(), $if));
        $this->assertSame($ifFixed, $fixer->fix($this->getTestFile(), $ifFixed));
    }

    public function testFixControlClosingParenthesesKeepsIndentation()
    {
        $fixer = new Fixer();

        $if = 'if(true === true
            && true === true
        )    {';

        $ifFixed = 'if (true === true
            && true === true
        ) {';

        $this->assertSame($ifFixed, $fixer->fix($this->getTestFile(), $if));
        $this->assertSame($ifFixed, $fixer->fix($this->getTestFile(), $ifFixed));
    }

    public function testFixControlsWithParenthesesAndSuffixBraceProvider()
    {
        return array(
            array('if($test){', 'if ($test) {'),
            array('if( $test ){', 'if ($test) {'),
            array('if  (   $test ){', 'if ($test) {'),
            array('if  (($test1 || $test2) && $test3){', 'if (($test1 || $test2) && $test3) {'),
            array('if(($test1 || $test2) && $test3){', 'if (($test1 || $test2) && $test3) {'),
            array('if ($this->tesT ($test)) {', 'if ($this->tesT ($test)) {'),
            array('if ($this->testtesT ($test)) {', 'if ($this->testtesT ($test)) {'),
        );
    }

    public function testFixControlsWithPrefixBraceAndSuffixBrace()
    {
        $fixer = new Fixer();

        $else = '}else{';
        $elseFixed = '} else {';
        $this->assertSame($elseFixed, $fixer->fix($this->getTestFile(), $else));
        $this->assertSame($elseFixed, $fixer->fix($this->getTestFile(), $elseFixed));
    }

    public function testFixControlsWithPrefixBraceAndParenthesesAndSuffixBrace()
    {
        $fixer = new Fixer();

        $elseif = '}elseif($test){';
        $elseifFixed = '} elseif ($test) {';
        $this->assertSame($elseifFixed, $fixer->fix($this->getTestFile(), $elseif));
        $this->assertSame($elseifFixed, $fixer->fix($this->getTestFile(), $elseifFixed));
    }

    public function testFixControlsWithPrefixBraceAndParenthesesAndSuffixBraceInLambdas()
    {
        $fixer = new Fixer();

        $use = ')use($test){';
        $useFixed = ') use ($test) {';
        $this->assertSame($useFixed, $fixer->fix($this->getTestFile(), $use));
        $this->assertSame($useFixed, $fixer->fix($this->getTestFile(), $useFixed));
    }

    private function getTestFile($filename = __FILE__)
    {
        static $files = array();

        if (!isset($files[$filename])) {
            $files[$filename] = new \SplFileInfo($filename);
        }

        return $files[$filename];
    }
}
