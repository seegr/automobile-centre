<?php

namespace App\Types;

enum Severity
{
    case SUCCESS;
    case WARNING;
    case DANGER;
    case INFO;
}