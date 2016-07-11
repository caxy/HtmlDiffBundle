<?php

namespace Caxy\HtmlDiffBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class HtmlDiffExtension extends \Twig_Extension
{
    protected $container;

    protected $htmlDiff;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->htmlDiff = $this->container->get('caxy.html_diff');
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('htmldiff', array($this, 'htmlDiff')),
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