<?php

declare(strict_types=1);

namespace Hunternnm\LaravelRoadrunner\Support\Clockwork;

class ClockworkSupport extends \Clockwork\Support\Laravel\ClockworkSupport
{
    /**
     * @return bool
     */
    public function isCollectingData(): bool
    {
        return ($this->isEnabled() || $this->getConfig('collect_data_always', false))
            && !$this->isUriFiltered($this->app['request']->getRequestUri());
    }
}
