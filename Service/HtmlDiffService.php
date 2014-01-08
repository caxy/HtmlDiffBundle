<?php

namespace Caxy\HtmlDiffBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Caxy\HtmlDiff\HtmlDiff;

class HtmlDiffService
{
    protected $container;

    protected $specialCaseTags = array('strong', 'b', 'i', 'big', 'small', 'u', 'sub', 'sup', 'strike', 's', 'p');

    protected $encoding = 'UTF-8';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $encoding = $this->container->getParameter('caxy_html_diff.encoding');
        if (!empty($encoding)) {
            $this->encoding = $encoding;
        }

        $specialCaseTags = $this->container->getParameter('caxy_html_diff.special_case_tags');
        if (!empty($specialCaseTags)) {
            $this->specialCaseTags = $specialCaseTags;
        }
    }

    public function diff($oldText, $newText)
    {
        $diff = new HtmlDiff($oldText, $newText, $this->encoding, $this->specialCaseTags);

        return $diff->build();
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }

    public function getSpecialCaseTags()
    {
        return $this->specialCaseTags;
    }

    public function addSpecialCaseTag($tag)
    {
        if (!in_array($tag, $this->specialCaseTags)) {
            $this->specialCaseTags[] = $tag;
        }

        return $this;
    }

    public function removeSpecialCaseTag($tag)
    {
        if (($key = array_search($tag, $this->specialCaseTags)) !== false) {
            unset($this->specialCaseTags[$key]);
        }

        return $this;
    }
    
}