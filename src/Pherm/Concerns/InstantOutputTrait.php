<?php

namespace MilesChou\Pherm\Concerns;

trait InstantOutputTrait
{
    /**
     * @var bool
     */
    protected $instantOutput = false;

    /**
     * @return static
     */
    public function disableInstantOutput()
    {
        $this->instantOutput = true;
        return $this;
    }

    /**
     * @return static
     */
    public function enableInstantOutput()
    {
        $this->instantOutput = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInstantOutput(): bool
    {
        return $this->instantOutput;
    }
}
