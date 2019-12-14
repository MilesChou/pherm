<?php

namespace MilesChou\Pherm\Concerns;

use MilesChou\Pherm\Input\InputInterface;
use MilesChou\Pherm\Output\OutputInterface;

trait IoAwareTrait
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    /**
     * @param InputInterface $input
     * @return static
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @param OutputInterface $output
     * @return static
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }
}
