<?php
namespace Framework\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Extension series concerning texts
 * Class TextExtension
 * @package Framework\Twig
 */
class TextExtension extends AbstractExtension
{


    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('excerpt', [$this, 'excerpt'])
        ];
    }


    /**
     * Returns an extract of the content
     * @param string $content
     * @param int $maxLength
     * @return string
     */
    public function excerpt($content, $maxLength = 100)
    {
        if (mb_strlen($content) > $maxLength) {
            $excerpt = mb_substr($content, 0, $maxLength);
            $lastSpace = mb_strrpos($excerpt, ' ');
            return mb_substr($excerpt, 0, $lastSpace) . '...';
        }
        return $content;
    }
}
