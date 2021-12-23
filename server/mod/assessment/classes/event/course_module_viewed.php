<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace mod_assessment\event;

/**
 * {@inheritDoc}
 */
class course_module_viewed extends \core\event\course_module_viewed {
    protected function init() {
        $this->data['objecttable'] = 'assessment';
        parent::init();
    }
}
