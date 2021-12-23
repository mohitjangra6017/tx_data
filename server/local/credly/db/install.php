<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Michael Geering <michael.geering@kineo.com>
 */

function xmldb_local_credly_install()
{
    set_config('opt_out_disclaimer', get_string('user_preferences:opt_out:disclaimer', 'local_credly'), 'local_credly');
}
