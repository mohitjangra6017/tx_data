<?php

namespace local_credly\webapi\resolver\mutation;

use core\webapi\execution_context;
use core\webapi\middleware\require_login;
use core\webapi\mutation_resolver;
use core\webapi\resolver\has_middleware;
use local_credly\Sync;

final class sync_badges implements mutation_resolver, has_middleware
{
    public static function get_middleware(): array
    {
        return [
            new require_login(),
        ];
    }

    public static function resolve(array $args, execution_context $ec)
    {
        (new Sync())->synchroniseWithCredly();
        return ['syncfinished' => true];
    }
}