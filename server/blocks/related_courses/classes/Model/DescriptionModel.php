<?php

namespace block_related_courses\Model;

use coding_exception;
use context;
use DOMDocument;

/**
 * Description of description_model
 *
 * @author paulstanyer
 */
class DescriptionModel
{
    /** @var string */
    private $source;

    /** @var string */
    private $summary;

    /** @var string */
    private $image;

    /** @var string */
    private $banner;

    /**
     * DescriptionModel constructor.
     *
     * @param string $source
     * @param context $context
     * @throws coding_exception
     */
    public function __construct(string $source, context $context)
    {
        $this->source = $source;
        if (!empty(strip_tags($this->source, ['img']))) {
            $this->parseDescription($context);
        } else {
            $this->summary = '';
        }
    }

    /**
     * @return string
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getBanner(): ?string
    {
        return $this->banner;
    }

    /**
     * @param string $banner
     */
    public function setBanner(string $banner): void
    {
        $this->banner = $banner;
    }

    /**
     * @param context $context
     */
    protected function parseDescription(context $context): void
    {
        global $CFG;
        require_once($CFG->dirroot . '/lib/filelib.php');

        $dom = new DOMDocument();
        $dom->loadHTML($this->source);

        // We expect two images, first is banner, second is 'image'.
        foreach ($dom->getElementsByTagName('img') as $img) {
            // Rewrite URL sensitive to context.
            if ($context instanceof \context_course) {
                $img_path = file_rewrite_pluginfile_urls(
                    $img->getAttribute('src'),
                    'pluginfile.php',
                    $context->id,
                    'course',
                    'summary',
                    null
                );
            } else {
                $img_path = file_rewrite_pluginfile_urls(
                    $img->getAttribute('src'),
                    'pluginfile.php',
                    $context->id,
                    'coursecat',
                    'description',
                    null
                );
            }

            // Save to banner first, then 'image'.
            if (empty($this->banner)) {
                $this->banner = $img_path;
            } else {
                $this->image = $img_path;
                break;
            }
        }
        // Detect if summary contains ANY text
        if (!empty(strip_tags($this->source))) {
            // This removes html including the images, links etc.
            $this->summary = strip_tags($this->source, '<b><strong><i><em><p><ul><li><ol><br><br/><br />');
        } else {
            $this->summary = '';
        }
    }
}