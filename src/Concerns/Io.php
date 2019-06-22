<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Exceptions\NotInteractiveTerminal;
use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Contracts\OutputStream;

/**
 * Screen size of terminal
 */
trait Io
{
    /**
     * @var InputStream
     */
    private $input;

    /**
     * @var OutputStream
     */
    private $output;

    /**
     * @param InputStream $input
     * @return static
     */
    public function setInput(InputStream $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @param OutputStream $output
     * @return static
     */
    public function setOutput(OutputStream $output)
    {
        $this->output = $output;

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

    public function supportsColour(): bool
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return false !== getenv('ANSICON') || 'ON' === getenv('ConEmuANSI') || 'xterm' === getenv('TERM');
        }

        return $this->isInteractive();
    }

    public function clear(): void
    {
        $this->output->write("\033[2J");
    }

    public function clearLine(): void
    {
        $this->output->write("\033[2K");
    }

    public function clearDown(): void
    {
        $this->output->write("\033[J");
    }

    public function clean(): void
    {
        foreach (range(0, $this->height()) as $rowNum) {
            $this->moveCursorToRow($rowNum);
            $this->clearLine();
        }
    }

    public function enableCursor(): void
    {
        $this->output->write("\033[?25h");
    }

    public function disableCursor(): void
    {
        $this->output->write("\033[?25l");
    }

    public function moveCursorToTop(): void
    {
        $this->output->write("\033[H");
    }

    public function moveCursorToRow(int $rowNumber): void
    {
        $this->output->write(sprintf("\033[%d;0H", $rowNumber));
    }

    public function moveCursorToColumn(int $column): void
    {
        $this->output->write(sprintf("\033[%dC", $column));
    }

    public function moveCursor(int $column, int $row): void
    {
        $this->output->write(sprintf("\033[%d;%dH", $row, $column));
    }

    public function showSecondaryScreen(): void
    {
        $this->output->write("\033[?47h");
    }

    public function showPrimaryScreen(): void
    {
        $this->output->write("\033[?47l");
    }

    public function read(int $bytes): string
    {
        $buffer = '';
        $this->input->read($bytes, function ($data) use (&$buffer) {
            $buffer .= $data;
        });
        return $buffer;
    }

    public function write(string $buffer): void
    {
        $this->output->write($buffer);
    }

    public function writeCursor(int $column, int $row, string $buffer): void
    {
        $this->moveCursor($column, $row);
        $this->output->write($buffer);
    }
}
