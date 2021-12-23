<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2020 Kineo
 */

namespace mod_assessment\factory;

use Exception;
use mod_assessment\model\question;
use mod_assessment\model\stage;
use mod_assessment\model\version;
use mod_assessment\question\edit_form;

class question_form_factory
{

    /**
     * @param stage $stage
     * @param question $question
     * @param version $version
     * @param question|null $parent
     * @return edit_form
     * @throws Exception
     */
    public static function create(stage $stage, question $question, version $version, ?question $parent = null): edit_form
    {
        $formclass = "mod_assessment\\question\\{$question->type}\\form\\edit";
        if (!class_exists($formclass)) {
            throw new Exception("No question form found for type ({$question->type})");
        }

        return new $formclass(null, ['stage' => $stage, 'question' => $question, 'version' => $version, 'parent' => $parent]);
    }
}
