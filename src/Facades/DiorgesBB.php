<?php

namespace Diorgesl\DiorgesBB\Facades;

use Illuminate\Support\Facades\Facade;

class DiorgesBB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'diorgesbb';
    }
}
