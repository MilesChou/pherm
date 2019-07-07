<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Control;
use MilesChou\Pherm\Exceptions\NotInteractiveTerminal;

trait IoTrait
{
    use IoAwareTrait;

    /**
     * @var Control
     */
    private $control;

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
            throw NotInteractiveTerminal::inputNotInteractive();
        }

        if (!$this->output->isInteractive()) {
            throw NotInteractiveTerminal::outputNotInteractive();
        }
    }

    /**
     * @param Control $control
     * @return static
     */
    public function setControl(Control $control)
    {
        $this->control = $control;

        return $this;
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
