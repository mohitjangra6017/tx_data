<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon.Thornett
 */

namespace local_core\Hook;

use block_edit_form;
use totara_core\hook\base;

/**
 * Class BlockEditForm
 * @package local_core\hook
 */
class BlockEditForm extends base
{

    /**
     * @var block_edit_form $form
     */
    private $form;

    /**
     * BlockEditForm constructor.
     * @param block_edit_form $blockEditForm
     */
    public function __construct(block_edit_form $blockEditForm)
    {
        $this->form = $blockEditForm;
    }

    /**
     * @return block_edit_form
     */
    public function getForm(): block_edit_form
    {
        return $this->form;
    }
}