<?php

namespace Chaoswey\TaiwanIdValidator\Validators;

use Chaoswey\TaiwanIdValidator\Contracts\ValidatorInterface;

class BanValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if (!is_string($value) && !is_int($value)) {
            return false;
        }

        $input = (string)$value;

        if (!preg_match('/^\d{8}$/', $input)) {
            return false;
        }

        $applyOldRules = (bool)($options['applyOldRules'] ?? false);
        $coefficients = [1, 2, 1, 2, 1, 2, 4, 1];
        $digits = array_map(static fn(string $digit): int => (int)$digit, str_split($input));

        $checksum = array_reduce(
            $this->zipWith($coefficients, $digits, [$this, 'multiply']),
            static fn(int $carry, int $product): int => $carry + ($product % 10) + intdiv($product, 10),
            0
        );

        $seventhIsSeven = (int)$input[6] === 7;
        $remainder = $checksum % 10;

        if ($remainder === 0) {
            return true;
        }

        if ($seventhIsSeven && ($checksum + 1) % 10 === 0) {
            return true;
        }

        if (!$applyOldRules && $seventhIsSeven && in_array($remainder, [4, 5], true)) {
            return true;
        }

        return false;
    }

    /**
     * @param callable(int, int):int $callback
     */
    private function zipWith(array $first, array $second, callable $callback): array
    {
        $length = min(count($first), count($second));
        $result = [];

        for ($i = 0; $i < $length; $i++) {
            $result[$i] = $callback($first[$i], $second[$i]);
        }

        return $result;
    }

    private function multiply(int $a, int $b): int
    {
        return $a * $b;
    }
}
