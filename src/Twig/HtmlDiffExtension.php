<?php

namespace Caxy\HtmlDiffBundle\Twig;

use Caxy\HtmlDiffBundle\Service\HtmlDiffService;

class HtmlDiffExtension extends \Twig\Extension\AbstractExtension
{
    protected $container;

    protected $htmlDiff;

    public function __construct(HtmlDiffService $htmlDiff)
    {
        $this->htmlDiff = $htmlDiff;
    }

    public function getFunctions(): array
    {
        return array(
            new \Twig\TwigFunction('htmldiff', array($this, 'htmlDiff'), array('is_safe' => array('html'))),
        );
    }

    public function htmlDiff($a, $b)
    {
        return $this->htmlDiff->diff((string) $a, (string) $b);
    }

    public function getName()
    {
        return 'caxy_htmldiff_extension';
    }
}
