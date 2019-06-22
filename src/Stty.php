<?php

namespace MilesChou\Pherm;

use RuntimeException;

class Stty
{
    /**
     * @var string
     */
    private $originalConfiguration;

    /**
     * @param string $parameter
     * @return bool|string
     */
    public function exec($parameter = '')
    {
        if (!function_exists('proc_open')) {
            throw new RuntimeException("Expect 'proc_open' function");
        }

        $descriptorspec = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open(
            'stty ' . $parameter,
            $descriptorspec,
            $pipes,
            null,
            null,
            ['suppress_errors' => true]
        );

        if (is_resource($process)) {
            $info = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);

            return $info;
        } else {
            throw new RuntimeException("Execute 'stty -a' error");
        }
    }

    /**
     * @return array
     */
    public function parseAll(): array
    {
        $output = $this->exec('-a');
        [$rows, $columns] = explode(' ', exec('stty size'));

        return [
            'columns' => (int)$columns,
            'echo' => (bool)preg_match('/[^-]echo/', $output),
            'icanon' => (bool)preg_match('/[^-]icanon/', $output),
            'rows' => (int)$rows,
        ];
    }

    public function restore(): void
    {
        $this->exec($this->originalConfiguration);
    }

    public function store(): void
    {
        $this->originalConfiguration = $this->exec('-g');
    }
}
