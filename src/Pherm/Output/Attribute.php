<?php

namespace MilesChou\Pherm\Output;

use MilesChou\Pherm\Contracts\Attribute as AttributeContract;
use MilesChou\Pherm\Contracts\OutputStream;

abstract class Attribute implements AttributeContract
{
    /**
     * @var int|null
     */
    protected $lastFg = self::INVALID;

    /**
     * @var int|null
     */
    protected $lastBg = self::INVALID;

    /**
     * @var OutputStream
     */
    protected $output;

    /**
     * @param OutputStream $output
     */
    public function __construct(OutputStream $output)
    {
        $this->output = $output;
    }

    /**
     * Just for testing
     *
     * @return OutputStream
     */
    public function getOutput(): OutputStream
    {
        return $this->output;
    }

    public function send(?int $fg, ?int $bg): void
    {
        if ($fg === $this->lastFg && $bg === $this->lastBg) {
            return;
        }

        // See https://en.wikipedia.org/wiki/ANSI_escape_code#SGR_parameters for more information about SGR0
        $this->output->write("\033[m");

        $this->sendColor($fg, $bg);

        if ($fg !== self::COLOR_DEFAULT && ($fg & self::BOLD) !== 0) {
            // TODO: For Mac, too
            $this->output->write("\033[1m");
        }

        if ($bg !== self::COLOR_DEFAULT && ($bg & self::BOLD) !== 0) {
            // TODO: For Mac, too
            $this->output->write("\033[5m");
        }

        if ($fg !== self::COLOR_DEFAULT && ($fg & self::UNDER_LINE) !== 0) {
            // TODO: no data
        }

        if ($fg !== self::COLOR_DEFAULT && ($fg & self::REVERSE) !== 0) {
            // TODO: no data
        }

        $this->lastFg = $fg;
        $this->lastBg = $bg;
    }

    /**
     * @param int|null $fg
     * @param int|null $bg
     */
    protected function sendColor(?int $fg, ?int $bg): void
    {
        if ($fg === self::COLOR_DEFAULT && $bg === self::COLOR_DEFAULT) {
            return;
        }

        $fgCol = $fg & 0x1FF;
        $bgCol = $bg & 0x1FF;

        if ($fg !== self::COLOR_DEFAULT && $bg !== self::COLOR_DEFAULT) {
            $this->write($fgCol, $bgCol);
            return;
        }

        if ($fg !== self::COLOR_DEFAULT) {
            $this->writeForeground($fgCol);
            return;
        }

        if ($bg !== self::COLOR_DEFAULT) {
            $this->writeBackground($bgCol);
            return;
        }
    }

    /**
     * @param int $fg
     * @param int $bg
     */
    abstract protected function write(int $fg, int $bg): void;

    /**
     * @param int $fg
     */
    abstract protected function writeForeground(int $fg): void;

    /**
     * @param int $bg
     */
    abstract protected function writeBackground(int $bg): void;
}
