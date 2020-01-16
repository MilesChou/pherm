<?php

namespace MilesChou\Pherm\Input;

use InvalidArgumentException;

use function get_resource_type;
use function is_resource;
use function stream_get_meta_data;
use function strpos;

class InputStream implements InputInterface
{
    /**
     * @var resource
     */
    private $stream;

    public function __construct($stream = STDIN)
    {
        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new InvalidArgumentException('Expected a valid stream');
        }

        $meta = stream_get_meta_data($stream);
        if (strpos($meta['mode'], 'r') === false && strpos($meta['mode'], '+') === false) {
            throw new InvalidArgumentException('Expected a readable stream');
        }

        $this->stream = $stream;
    }

    public function isInteractive(): bool
    {
        return posix_isatty($this->stream);
    }

    public function read(int $numBytes, callable $callback): void
    {
        $buffer = fread($this->stream, $numBytes);
        $callback($buffer);
    }
}
