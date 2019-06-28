<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Contracts\InputStream;
use MilesChou\Pherm\Contracts\OutputStream;

trait IoAwareTrait
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
     * @return InputStream
     */
    public function getInput(): InputStream
    {
        return $this->input;
    }

    /**
     * @return OutputStream
     */
    public function getOutput(): OutputStream
    {
        return $this->output;
    }

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
}
