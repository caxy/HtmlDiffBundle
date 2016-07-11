<?php

namespace Caxy\HtmlDiffBundle\Service;

use Caxy\HtmlDiff\HtmlDiffConfig;
use Caxy\HtmlDiff\HtmlDiff;

/**
 * Class HtmlDiffService
 * @package Caxy\HtmlDiffBundle\Service
 */
class HtmlDiffService
{
    /**
     * @var HtmlDiffConfig
     */
    protected $config;

    /**
     * @return HtmlDiffConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param HtmlDiffConfig $config
     *
     * @return HtmlDiffService
     */
    public function setConfig(HtmlDiffConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param string     $oldText
     * @param string     $newText
     * @param null|bool  $groupDiffs
     * @param null|array $specialCaseChars
     *
     * @return string
     */
    public function diff($oldText, $newText, $groupDiffs = null, $specialCaseChars = null)
    {
        // Copy the config object, so that these changes are not persisted.
        $config = clone $this->config;
        if ($groupDiffs !== null) {
            $config->setGroupDiffs($groupDiffs);
        }
        
        if ($specialCaseChars !== null) {
            $config->setSpecialCaseChars($specialCaseChars);
        }

        $diff = HtmlDiff::create($oldText, $newText, $config);

        return $diff->build();
    }

    /**
     * @return bool
     *
     * @deprecated since 0.1.0
     */
    public function getGroupDiffs()
    {
        return $this->config->isGroupDiffs();
    }

    /**
     * @param $boolean
     *
     * @return $this
     *
     * @deprecated since 0.1.0
     */
    public function setGroupDiffs($boolean)
    {
        $this->config->setGroupDiffs($boolean);
        
        return $this;
    }

    /**
     * @return string
     *
     * @deprecated since 0.1.0
     */
    public function getEncoding()
    {
        return $this->config->getEncoding();
    }

    /**
     * @param $encoding
     *
     * @return $this
     *
     * @deprecated since 0.1.0
     */
    public function setEncoding($encoding)
    {
        $this->config->setEncoding($encoding);

        return $this;
    }

    /**
     * @return array|null
     *
     * @deprecated since 0.1.0
     */
    public function getSpecialCaseTags()
    {
        return $this->config->getSpecialCaseChars();
    }

    /**
     * @param $tag
     *
     * @return $this
     *
     * @deprecated since 0.1.0
     */
    public function addSpecialCaseTag($tag)
    {
        $this->config->addSpecialCaseTag($tag);

        return $this;
    }

    /**
     * @param $tag
     *
     * @return $this
     *
     * @deprecated since 0.1.0
     */
    public function removeSpecialCaseTag($tag)
    {
        $this->config->removeSpecialCaseTag($tag);

        return $this;
    }

    /**
     * @param array $chars
     *
     * @deprecated since 0.1.0
     */
    public function setSpecialCaseChars(array $chars)
    {
        $this->config->setSpecialCaseChars($chars);
    }

    /**
     * @return array|null
     *
     * @deprecated since 0.1.0
     */
    public function getSpecialCaseChars()
    {
        return $this->config->getSpecialCaseChars();
    }

    /**
     * @param $char
     *
     * @deprecated since 0.1.0
     */
    public function addSpecialCaseChar($char)
    {
        $this->config->addSpecialCaseChar($char);
    }

    /**
     * @param $char
     *
     * @deprecated since 0.1.0
     */
    public function removeSpecialCaseChar($char)
    {
        $this->config->removeSpecialCaseChar($char);
    }
}
