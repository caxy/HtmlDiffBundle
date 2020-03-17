<?php

namespace Caxy\HtmlDiffBundle\Twig;

use Caxy\HtmlDiffBundle\Service\HtmlDiffService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HtmlDiffExtension extends AbstractExtension
{
    protected $container;

    protected $htmlDiff;

    public function __construct(HtmlDiffService $htmlDiff)
    {
        $this->htmlDiff = $htmlDiff;
    }

    public function getFunctions()
    {
        return array(
            new TwigFilter('htmldiff', array($this, 'htmlDiff'), array('is_safe' => array('html'))),
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