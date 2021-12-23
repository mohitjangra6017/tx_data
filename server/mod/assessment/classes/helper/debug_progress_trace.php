<?php

namespace mod_assessment\helper;

use text_progress_trace;

class debug_progress_trace extends text_progress_trace
{

    /**
     * Output the trace message.
     *
     * @param string $message
     * @param int $depth
     * @return void Output is echo'd
     */
    public function output($message, $depth = 0, $eol = '')
    {
        mtrace(str_repeat('  ', $depth) . $message, $eol);
    }

}