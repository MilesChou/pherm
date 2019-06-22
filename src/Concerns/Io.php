<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Exceptions\NotInteractiveTerminal;
use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Contracts\OutputStream;

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

    public function clear()
    {
        $this->output->write("\033[2J");

        return $this;
    }

    public function clearLine()
    {
        $this->output->write("\033[2K");

        return $this;
    }

    public function clearDown()
    {
        $this->output->write("\033[J");

        return $this;
    }

    public function clean()
    {
        foreach (range(0, $this->height()) as $rowNum) {
            $this->moveCursorToRow($rowNum);
            $this->clearLine();
        }

        return $this;
    }

    public function enableCursor()
    {
        $this->output->write("\033[?25h");

        return $this;
    }

    public function disableCursor()
    {
        $this->output->write("\033[?25l");

        return $this;
    }

    public function moveCursorToTop($column = 0)
    {
        $this->moveCursor($column, 0);

        return $this;
    }

    public function moveCursorToMiddle($column = 0)
    {
        $this->moveCursor($column, $this->height() / 2);

        return $this;
    }

    public function moveCursorToCenter($columnDelta = 0)
    {
        $this->moveCursor($this->width() / 2 + $columnDelta, $this->height() / 2);

        return $this;
    }

    public function moveCursorToDown($column = 0)
    {
        $this->moveCursor($column, $this->height());

        return $this;
    }

    public function moveCursorToEnd()
    {
        $this->moveCursor($this->width(), $this->height());

        return $this;
    }

    public function moveCursorToRow(int $rowNumber)
    {
        $this->output->write(sprintf("\033[%d;0H", $rowNumber));

        return $this;
    }

    public function moveCursorToColumn(int $column)
    {
        $this->output->write(sprintf("\033[%dC", $column));

        return $this;
    }

    public function moveCursor(int $column, int $row)
    {
        $this->output->write(sprintf("\033[%d;%dH", $row, $column));

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

    public function read(int $bytes): string
    {
        $buffer = '';
        $this->input->read($bytes, function ($data) use (&$buffer) {
            $buffer .= $data;
        });
        return $buffer;
    }

    public function write(string $buffer)
    {
        $this->output->write($buffer);

        return $this;
    }

    public function writeCursor(int $column, int $row, string $buffer)
    {
        $this->moveCursor($column, $row);
        $this->output->write($buffer);

        return $this;
    }
}
