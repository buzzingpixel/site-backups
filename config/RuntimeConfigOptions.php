<?php

declare(strict_types=1);

namespace Config;

enum RuntimeConfigOptions
{
    case USE_WHOOPS_ERROR_HANDLING;

    case SMRC_DB_PASSWORD;
    case BUZZINGPIXEL_DB_PASSWORD;
    case MOVIE_BYTE_DB_PASSWORD;
    case NIGHT_OWL_DB_PASSWORD;
}
