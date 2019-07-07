<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Control;
use MilesChou\Pherm\CursorHelper;
use MilesChou\Pherm\Exceptions\NotInteractiveTerminal;

trait IoTrait
{
    use IoAwareTrait;

    /**
     * @var Control
     */
    private $control;

    /**
     * @var CursorHelper
     */
    private $cursorHelper;

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

    /**
     * @param CursorHelper $cursorHelper
     * @return static
     */
    public function setCursorHelper(CursorHelper $cursorHelper)
    {
        $this->cursorHelper = $cursorHelper;

        return $this;
    }

    public function supportsColour(): bool
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI') || 'xterm' === getenv('TERM');
        }

        return $this->isInteractive();
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
