<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Contracts\OutputStream;
use MilesChou\Pherm\Control;
use MilesChou\Pherm\Cursor;
use MilesChou\Pherm\Exceptions\NotInteractiveTerminal;

trait IoTrait
{
    use IoAwareTrait;

    /**
     * @var Control
     */
    private $control;

    /**
     * @var Cursor
     */
    private $cursor;

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
     * @param Cursor $cursor
     * @return static
     */
    public function setCursor(Cursor $cursor)
    {
        $this->cursor = $cursor;

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
