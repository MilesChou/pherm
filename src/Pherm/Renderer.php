<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\AttributeTrait;
use MilesChou\Pherm\Output\OutputInterface;
use MilesChou\Pherm\Support\Char;

class Renderer
{
    use AttributeTrait;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var CellBuffer
     */
    private $outputBuffer;

    /**
     * @var Control
     */
    private $control;

    /**
     * @param OutputInterface $output
     * @param Control $control
     */
    public function __construct(OutputInterface $output, Control $control)
    {
        $this->output = $output;
        $this->control = $control;

        $tty = $control->tty();

        $this->outputBuffer = new CellBuffer($tty->width(), $tty->height());
    }

    /**
     * @param CellBuffer $buffer
     */
    public function renderBuffer(CellBuffer $buffer): void
    {
        $attribute = $this->getAttribute();

        for ($y = 0; $y < $this->outputBuffer->height(); $y++) {
            $lineOffset = $y * $this->outputBuffer->width();
            for ($x = 0; $x < $this->outputBuffer->width();) {
                $cellOffset = $lineOffset + $x;

                $back = $buffer->cells[$cellOffset];

                if ($back[0] < ' ') {
                    $back[0] = ' ';
                }

                $w = Char::width($back[0]);

                if ($back === $this->outputBuffer->cells[$cellOffset]) {
                    $x += $w;
                    continue;
                }

                $this->outputBuffer->cells[$cellOffset] = $back;

                if ($back[1] !== $this->lastFg || $back[2] !== $this->lastBg) {
                    $this->output->write($attribute->generate($back[1], $back[2]));
                    $this->lastFg = $back[1];
                    $this->lastBg = $back[2];
                }

                if ($w === 2 && $x === $this->outputBuffer->width() - 1) {
                    $this->output->write(' ');
                } else {
                    $this->output->write($this->control->cup($y + 1, $x + 1));
                    $this->output->write($back[0]);
                    if ($w === 2) {
                        $next = $cellOffset + 1;
                        $this->outputBuffer->cells[$next] = [
                            "\0",
                            $back[1],
                            $back[2],
                        ];
                    }
                }

                $x += $w;
            }
        }
    }
}
