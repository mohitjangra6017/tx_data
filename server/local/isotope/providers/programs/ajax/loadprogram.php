<?php
global $CFG, $PAGE, $DB;

require_once dirname(__FILE__) . '/../../../../../config.php';
require_once $CFG->dirroot . '/totara/program/program.class.php';

use isotopeprovider_programs\Provider as ProgramsProvider;

$programId = required_param('programid', PARAM_INT);
$blockInstanceId = required_param('instanceid', PARAM_INT);
require_login();

$error = json_encode(["error" => get_string('error:unabletoloadprogramdata', 'isotopeprovider_programs')]);

if (!(new program($programId))->is_viewable()) {
    echo $error;
    exit;
}
/** @var block_isotope $blockInstance */
$blockInstance = block_instance('isotope', $DB->get_record('block_instances', ['id' => $blockInstanceId]));

$PAGE->set_context($blockInstance->context);
$PAGE->set_url('/local/isotope/providers/programs/ajax/loadprogram.php');

if (!$blockInstance->user_can_view()) {
    echo $error;
    exit;
}

$provider = new ProgramsProvider();
$provider->setProgramId($programId);

$config = $blockInstance->getProviderConfig($provider);
if (empty($config)) {
    echo $error;
    exit;
}

$provider->setConfig($config);

$loader = new Twig_Loader_Filesystem([$provider->getTemplateDirectory()]);
$twig = new Twig_Environment($loader, ['debug' => true]);

$twig->addFunction(
    new Twig_SimpleFunction(
        'get_string',
        function ($name, $plugin = 'core', $a = null) {
            return format_text(get_string($name, $plugin, $a), FORMAT_HTML);
        },
        ['is_safe' => ['all']]
    )
);

$twig->addFunction(
    new Twig_SimpleFunction(
        'render_pix_icon',
        function ($name, $alt, $component = 'core') {
            global $OUTPUT;
            return $OUTPUT->render(new pix_icon($name, $alt, $component));
        }
    )
);

$twig->addFilter(
    new Twig_SimpleFilter(
        'format_html',
        function ($text) {
            return format_text($text, FORMAT_HTML);
        },
        ['is_safe' => ['all']]
    )
);

$provider->twigExtensions($twig);

$items = $provider->load();

// Get TWIG templates.
$itemsTemplate = $twig->load('coursesets.twig');
$courseSetsFilterTemplate = $twig->load('filter_coursesets.twig');

$itemsHtml = $itemsTemplate->render($items);
$courseSetsFilterHtml = $courseSetsFilterTemplate->render($items);

$data = [
    'items' => $itemsHtml,
    'coursesetsfilter' => $courseSetsFilterHtml,
];

echo json_encode($data);
exit;