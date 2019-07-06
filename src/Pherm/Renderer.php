<?php

namespace MilesChou\Pherm;

use MilesChou\Pherm\Concerns\PositionAwareTrait;
use MilesChou\Pherm\Concerns\SizeAwareTrait;
use MilesChou\Pherm\Contracts\Attribute;
use MilesChou\Pherm\Contracts\Cursor;
use MilesChou\Pherm\Contracts\OutputStream;
use MilesChou\Pherm\Contracts\Renderer as RendererContract;
use MilesChou\Pherm\Contracts\Terminal;
use MilesChou\Pherm\Support\Char;

class Renderer implements RendererContract
{
    use PositionAwareTrait;
    use SizeAwareTrait;

    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * @var Cursor
     */
    private $cursor;

    /**
     * @var OutputStream
     */
    private $output;

    /**
     * @var CellBuffer
     */
    private $outputBuffer;

    /**
     * @param Terminal $terminal
     */
    public function __construct(Terminal $terminal)
    {
        $this->attribute = $terminal->getAttribute();
        $this->cursor = $terminal->getCursor();
        $this->output = $terminal->getOutput();

        $this->outputBuffer = new CellBuffer($terminal->width(), $terminal->height());
    }

    public function renderBuffer(CellBuffer $buffer): void
    {
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

                $this->output->write($this->attribute->generate($back[1], $back[2]));

                if ($w === 2 && $x === $this->outputBuffer->width() - 1) {
                    $this->output->write(' ');
                } else {
                    $this->cursor->instant($x + 1, $y + 1);
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