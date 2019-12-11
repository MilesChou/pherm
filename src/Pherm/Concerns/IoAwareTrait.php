<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Contracts\Input;
use MilesChou\Pherm\Contracts\Output;

trait IoAwareTrait
{
    /**
     * @var Input
     */
    private $input;

    /**
     * @var Output
     */
    private $output;

    /**
     * @return Input
     */
    public function getInput(): Input
    {
        return $this->input;
    }

    /**
     * @return Output
     */
    public function getOutput(): Output
    {
        return $this->output;
    }

    /**
     * @param Input $input
     * @return static
     */
    public function setInput(Input $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @param Output $output
     * @return static
     */
    public function setOutput(Output $output)
    {
        $this->output = $output;

        return $this;
    }
}
