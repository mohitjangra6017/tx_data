<?php
/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */

define('CLI_SCRIPT', true);

require(__DIR__.'/../../config.php');
global $CFG;
require_once($CFG->libdir . '/clilib.php');

[$options, $unrecognized] = cli_get_params(['email' => '', 'help' => false], ['h' => 'help']);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized), 2);
}

if (!$options['email'] || $options['help']) {
    $help =
        'Test email sending from TKE.

Options:
-h, --help            Print out this help
--email="<email_address>"            Recipient email address to test with.
';

    echo $help;
    exit(0);
}

$guest = guest_user();
$guest->email = $options['email'];
$noReplyUser = \core_user::get_noreply_user();

$CFG->debug = (E_ALL | E_STRICT);
$CFG->debugdisplay = 1;

if ($CFG->noemailever) {
    mtrace('$CFG->noemailever currently set to "true", bypassing this for script execution');
    $CFG->noemailever = false;
}

if (email_should_be_diverted($options['email'])) {
    mtrace('Bypassing email diversion set in "$CFG->divertallemailsto" or "$CFG->divertallemailsexcept" for script execution');
    $CFG->divertallemailsto = '';
    $CFG->divertallemailsexcept = '';
}

if (email_to_user($guest, $noReplyUser, 'Test email', 'cli initiated test')) {
    mtrace('Execution complete...');
} else {
    mtrace('Email failed to send...');
}

die;
