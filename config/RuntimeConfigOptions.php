<?php

declare(strict_types=1);

namespace Config;

enum RuntimeConfigOptions
{
    case USE_WHOOPS_ERROR_HANDLING;

    // SMRC
    case SMRC_DB_PASSWORD;
}
