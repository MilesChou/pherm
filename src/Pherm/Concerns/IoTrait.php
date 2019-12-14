<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Exceptions\NotInteractiveTerminal;

trait IoTrait
{
    use IoAwareTrait;

    public function clearDown()
    {
        $this->output->write("\033[J");

        return $this;
    }

    public function clearLine()
    {
        $this->output->write("\033[2K");

        return $this;
    }

    public function disableCursor()
    {
        $this->output->write("\033[?25l");

        return $this;
    }

    public function enableCursor()
    {
        $this->output->write("\033[?25h");

        return $this;
    }

    public function isInteractive(): bool
    {
        return $this->input->isInteractive() && $this->output->isInteractive();
    }

    public function mustBeInteractive(): void
    {
        if (!$this->input->isInteractive()) {
            throw new NotInteractiveTerminal('InputInterface stream is not interactive (non TTY)');
        }

        if (!$this->output->isInteractive()) {
            throw new NotInteractiveTerminal('Output stream is not interactive (non TTY)');
        }
    }

    public function showSecondaryScreen()
    {
        $this->output->write("\033[?47h");

        return $this;
    }

    public function showPrimaryScreen()
    {
        $this->output->write("\033[?47l");

        return $this;
    }
}
