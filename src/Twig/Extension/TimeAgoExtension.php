<?php

namespace App\Twig\Extension;

use Carbon\Carbon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TimeAgoExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [

            new TwigFilter('timeAgo', [$this, 'getDif']),
        ];
    }

    public function getDif($value)
    {
        return Carbon::make($value)->locale('ru')->diffForHumans();
    }
}
