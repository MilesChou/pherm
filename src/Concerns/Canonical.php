<?php

namespace MilesChou\Pherm\Concerns;

trait Canonical
{
    /**
     * @var bool
     */
    private $isCanonical = true;

    public function disableCanonicalMode(): void
    {
        if ($this->isCanonical) {
            exec('stty -icanon');
            $this->isCanonical = false;
        }
    }

    public function enableCanonicalMode(): void
    {
        if (!$this->isCanonical) {
            exec('stty icanon');
            $this->isCanonical = true;
        }
    }

    public function isCanonicalMode(): bool
    {
        return $this->isCanonical;
    }
}
