<?php
declare(strict_types=1);
namespace App\Domain\SharedKernel\VO;

enum QuarkType: string
{
    case UP = 'up';
    case DOWN = 'down';
    case CHARM = 'charm';
    case STRANGE = 'strange';
    case TOP = 'top';
    case BOTTOM = 'bottom';
}
