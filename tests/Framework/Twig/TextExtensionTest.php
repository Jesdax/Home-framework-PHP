<?php
namespace Tests\Framework\Twig;


use Framework\Twig\TextExtension;
use PHPUnit\Framework\TestCase;

class TextExtensionTest extends TestCase
{

    /**
     * @var TextExtension
     */
    private $testExtension;

    public function setUp(): void
    {
        $this->testExtension = new TextExtension();
    }


    public function testExcerptWithShortText()
    {
        $text = "Salut";
        $this->assertEquals($text, $this->testExtension->excerpt($text, 10));
    }

    public function testExcerptWithLongText()
    {
        $text = "Salut les gens";
        $this->assertEquals('Salut...', $this->testExtension->excerpt($text, 7));
        $this->assertEquals('Salut les...', $this->testExtension->excerpt($text, 12));
    }

}