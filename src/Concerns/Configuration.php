<?php

namespace MilesChou\Pherm\Concerns;

/**
 * Screen size of terminal
 */
trait Configuration
{
    /**
     * @var string
     */
    private $originalConfiguration;

    private function getOriginalConfiguration(): string
    {
        return $this->originalConfiguration ?: $this->originalConfiguration = exec('stty -g');
    }

    public function restoreOriginalConfiguration(): void
    {
        exec('stty ' . $this->getOriginalConfiguration());
    }
}
