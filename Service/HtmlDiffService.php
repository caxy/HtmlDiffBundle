<?php

namespace Caxy\HtmlDiffBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Caxy\HtmlDiff\HtmlDiff;

class HtmlDiffService
{
    const PARAMETER_SPECIAL_CASE_TAGS = 'special_case_tags';
    const PARAMETER_SPECIAL_CASE_CHARS = 'special_case_chars';
    const PARAMETER_ENCODING = 'encoding';
    const PARAMETER_GROUP_DIFFS = 'group_diffs';
    
    protected $container;

    protected $specialCaseTags;
    
    protected $specialCaseChars;
    
    protected $groupDiffs;

    protected $encoding = 'UTF-8';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->specialCaseTags  = HtmlDiff::$defaultSpecialCaseTags;
        $this->specialCaseChars = HtmlDiff::$defaultSpecialCaseChars;
        $this->groupDiffs       = HtmlDiff::$defaultGroupDiffs;
        
        $this->loadParameter(self::PARAMETER_ENCODING, $this->encoding);
        $this->loadParameter(self::PARAMETER_SPECIAL_CASE_TAGS, $this->specialCaseTags);
        $this->loadParameter(self::PARAMETER_SPECIAL_CASE_CHARS, $this->specialCaseChars);
        $this->loadParameter(self::PARAMETER_GROUP_DIFFS, $this->groupDiffs);
    }
    
    public function loadParameter($param, &$property)
    {
        $param = 'caxy_html_diff.' . $param;
        if ($this->container->hasParameter($param)) {
            $property = $this->container->getParameter($param);
        }
    }

    public function diff($oldText, $newText, $groupDiffs = null, $specialCaseChars = null)
    {
        if ($groupDiffs === null) {
            $groupDiffs = $this->groupDiffs;
        }
        
        if ($specialCaseChars === null) {
            $specialCaseChars = $this->specialCaseChars;
        }
        
        $diff = new HtmlDiff($oldText, $newText, $this->encoding, $this->specialCaseTags, $groupDiffs);
        
        if ($specialCaseChars !== null) {
            $diff->setSpecialCaseChars($specialCaseChars);
        }

        return $diff->build();
    }
    
    public function getGroupDiffs()
    {
        return $this->groupDiffs;
    }
    
    public function setGroupDiffs($boolean)
    {
        $this->groupDiffs = $boolean;
        
        return $this;
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
    
    public function setSpecialCaseChars(array $chars)
    {
        $this->specialCaseChars = $chars;
    }
    
    public function getSpecialCaseChars()
    {
        return $this->specialCaseChars;
    }
    
    public function addSpecialCaseChar($char)
    {
        if (!in_array($char, $this->specialCaseChars)) {
            $this->specialCaseChars[] = $char;
        }
    }
    
    public function removeSpecialCaseChar($char)
    {
        $key = array_search($char, $this->specialCaseChars);
        if ($key !== false) {
            unset($this->specialCaseChars[$key]);
        }
    }
}
