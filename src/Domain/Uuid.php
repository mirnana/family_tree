<?php

namespace App\Domain;

use JsonSerializable;
use function random_bytes;

final class Uuid implements JsonSerializable {
    private string $id;

    public function __construct(string $id) {
        $this->id = $id;
    }

    public static function generate(): self {
        $data = random_bytes(16);
        return new self(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
    }

    public function __toString(): string {
        return $this->id;
    }

    public function jsonSerialize(): string {
        return $this->id;
    }
}