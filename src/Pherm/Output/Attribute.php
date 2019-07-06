<?php

namespace MilesChou\Pherm\Output;

use MilesChou\Pherm\Contracts\Attribute as AttributeContract;
use MilesChou\Pherm\Contracts\OutputStream;

abstract class Attribute implements AttributeContract
{
    /**
     * @var string
     */
    protected $buffer = '';

    public function generate(?int $fg, ?int $bg): string
    {
        // See https://en.wikipedia.org/wiki/ANSI_escape_code#SGR_parameters for more information about SGR0
        $this->writeBuffer("\033[m");

        $this->writeBuffer($this->generateColor($fg, $bg));

        if ($fg !== self::COLOR_DEFAULT && ($fg & self::BOLD) !== 0) {
            // TODO: For Mac, too
            $this->writeBuffer("\033[1m");
        }

        if ($bg !== self::COLOR_DEFAULT && ($bg & self::BOLD) !== 0) {
            // TODO: For Mac, too
            $this->writeBuffer("\033[5m");
        }

        if ($fg !== self::COLOR_DEFAULT && ($fg & self::UNDER_LINE) !== 0) {
            // TODO: no data
        }

        if ($fg !== self::COLOR_DEFAULT && ($fg & self::REVERSE) !== 0) {
            // TODO: no data
        }

        return $this->flushBuffer();
    }

    /**
     * @param int|null $fg
     * @param int|null $bg
     * @return string
     */
    protected function generateColor(?int $fg, ?int $bg): string
    {
        if ($fg === self::COLOR_DEFAULT && $bg === self::COLOR_DEFAULT) {
            return '';
        }

        $fgCol = $fg & 0x1FF;
        $bgCol = $bg & 0x1FF;

        if ($fg !== self::COLOR_DEFAULT && $bg !== self::COLOR_DEFAULT) {
            return $this->generateBoth($fgCol, $bgCol);
        }

        if ($fg !== self::COLOR_DEFAULT) {
            return $this->generateForeground($fgCol);
        }

        if ($bg !== self::COLOR_DEFAULT) {
            return $this->genearateBackground($bgCol);
        }
    }

    /**
     * @return string
     */
    private function flushBuffer(): string
    {
        $string = $this->buffer;

        $this->buffer = '';

        return $string;
    }

    /**
     * @param string $buffer
     */
    private function writeBuffer(string $buffer): void
    {
        $this->buffer .= $buffer;
    }

    /**
     * @param int $fg
     * @param int $bg
     * @return string
     */
    abstract protected function generateBoth(int $fg, int $bg): string;

    /**
     * @param int $fg
     * @return string
     */
    abstract protected function generateForeground(int $fg): string;

    /**
     * @param int $bg
     * @return string
     */
    abstract protected function genearateBackground(int $bg): string;
}
