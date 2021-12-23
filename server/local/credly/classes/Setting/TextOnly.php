<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

namespace local_credly\Setting;

use admin_setting_heading;

class TextOnly extends admin_setting_heading
{
    public function get_setting()
    {
        global $CFG;
        $token = get_config('local_credly', 'webhooktoken');

        return  $token ? "{$CFG->wwwroot}/local/credly/endpoint.php?token={$token}" : '';
    }


    public function output_html($data, $query = '')
    {
        return format_admin_setting($this, $this->visiblename, $data, $this->description);
    }
}
