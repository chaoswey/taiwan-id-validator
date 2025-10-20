<?php

namespace Chaoswey\TaiwanIdValidator\Validators;

use Chaoswey\TaiwanIdValidator\Contracts\ValidatorInterface;

class IdCardValidator implements ValidatorInterface
{
    private const PATTERN_NATIONAL = '/^[A-Z][1,2]\d{8}$/';
    private const PATTERN_NEW_UI = '/^[A-Z][8,9]\d{8}$/';
    private const PATTERN_OLD_UI = '/^[A-Z][A-D]\d{8}$/';

    private const NEW_UI_CATEGORY_MAPPING = [
        '0' => 'foreignOrStateless',
        '1' => 'foreignOrStateless',
        '2' => 'foreignOrStateless',
        '3' => 'foreignOrStateless',
        '4' => 'foreignOrStateless',
        '5' => 'foreignOrStateless',
        '6' => 'foreignOrStateless',
        '7' => 'statelessResident',
        '8' => 'hkMacaoResident',
        '9' => 'mainlandChinaResident',
    ];

    public function validate(mixed $value, array $options = []): bool
    {
        if (!is_string($value) || strlen($value) !== 10) {
            return false;
        }

        $normalized = $this->normalizeOptions($options);

        if (
            $normalized['nationalId']
            && preg_match(self::PATTERN_NATIONAL, $value)
            && $this->verifyChecksum($value)
        ) {
            return true;
        }

        if (
            $normalized['uiNumber']['oldFormat']
            && preg_match(self::PATTERN_OLD_UI, $value)
            && $this->verifyChecksum($value)
        ) {
            return true;
        }

        if (
            $normalized['uiNumber']['newFormat']['enabled']
            && preg_match(self::PATTERN_NEW_UI, $value)
        ) {
            if (!$this->isNewFormatCategoryAllowed($value, $normalized['uiNumber']['newFormat']['categories'])) {
                return false;
            }

            return $this->verifyChecksum($value);
        }

        return false;
    }

    private function normalizeOptions(array $options): array
    {
        $defaults = [
            'nationalId' => true,
            'uiNumber' => [
                'oldFormat' => true,
                'newFormat' => [
                    'enabled' => true,
                    'categories' => [
                        'foreignOrStateless' => true,
                        'statelessResident' => true,
                        'hkMacaoResident' => true,
                        'mainlandChinaResident' => true,
                    ],
                ],
            ],
        ];

        $normalized = $defaults;

        if (array_key_exists('nationalId', $options)) {
            $normalized['nationalId'] = (bool) $options['nationalId'];
        }

        if (array_key_exists('uiNumber', $options)) {
            $normalized['uiNumber'] = $this->normalizeUiOptions($options['uiNumber'], $defaults['uiNumber']);
        }

        return $normalized;
    }

    private function normalizeUiOptions(mixed $value, array $defaults): array
    {
        if (is_bool($value)) {
            if ($value === false) {
                return [
                    'oldFormat' => false,
                    'newFormat' => [
                        'enabled' => false,
                        'categories' => [
                            'foreignOrStateless' => false,
                            'statelessResident' => false,
                            'hkMacaoResident' => false,
                            'mainlandChinaResident' => false,
                        ],
                    ],
                ];
            }

            return $defaults;
        }

        if (!is_array($value)) {
            return $defaults;
        }

        $oldFormat = array_key_exists('oldFormat', $value)
            ? (bool) $value['oldFormat']
            : $defaults['oldFormat'];

        $newFormat = $this->normalizeNewFormatOptions($value['newFormat'] ?? null, $defaults['newFormat']);

        return [
            'oldFormat' => $oldFormat,
            'newFormat' => $newFormat,
        ];
    }

    private function normalizeNewFormatOptions(mixed $value, array $defaults): array
    {
        if (is_bool($value)) {
            if ($value === false) {
                return [
                    'enabled' => false,
                    'categories' => [
                        'foreignOrStateless' => false,
                        'statelessResident' => false,
                        'hkMacaoResident' => false,
                        'mainlandChinaResident' => false,
                    ],
                ];
            }

            return $defaults;
        }

        if (!is_array($value)) {
            return $defaults;
        }

        $categories = [
            'foreignOrStateless' => (bool) ($value['foreignOrStateless'] ?? false),
            'statelessResident' => (bool) ($value['statelessResident'] ?? false),
            'hkMacaoResident' => (bool) ($value['hkMacaoResident'] ?? false),
            'mainlandChinaResident' => (bool) ($value['mainlandChinaResident'] ?? false),
        ];

        $enabled = array_reduce(
            $categories,
            static fn (bool $carry, bool $flag): bool => $carry || $flag,
            false
        );

        return [
            'enabled' => $enabled,
            'categories' => $enabled ? $categories : $defaults['categories'],
        ];
    }

    private function isNewFormatCategoryAllowed(string $value, array $categories): bool
    {
        $categoryCode = $value[2] ?? null;

        if ($categoryCode === null) {
            return false;
        }

        $categoryKey = self::NEW_UI_CATEGORY_MAPPING[$categoryCode] ?? 'foreignOrStateless';

        return (bool) ($categories[$categoryKey] ?? false);
    }

    private function verifyChecksum(string $input): bool
    {
        $localeCodeList = [
            1, 10, 19, 28, 37, 46, 55, 64, 39, 73,
            82, 2, 11, 20, 48, 29, 38, 47, 56, 65,
            74, 83, 21, 3, 12, 30,
        ];

        $residentCodeList = [
            0, 1, 2, 3, 4, 5, 6, 7, 4, 8,
            9, 0, 1, 2, 5, 3, 4, 5, 6, 7,
            8, 9, 2, 0, 1, 3,
        ];

        $charIndex = static fn (string $subject, int $offset): int => ord($subject[$offset]) - ord('A');

        $firstDigit = $localeCodeList[$charIndex($input, 0)] ?? null;

        if ($firstDigit === null) {
            return false;
        }

        $secondDigit = is_numeric($input[1])
            ? (int) $input[1]
            : ($residentCodeList[$charIndex($input, 1)] ?? null);

        if ($secondDigit === null) {
            return false;
        }

        $digits = array_map(static fn (string $digit): int => (int) $digit, str_split(substr($input, 2)));
        $idInDigits = array_merge([$firstDigit, $secondDigit], $digits);

        $coefficients = [1, 8, 7, 6, 5, 4, 3, 2, 1, 1];

        $sum = array_reduce(
            $this->zipWith($idInDigits, $coefficients),
            static fn (int $carry, int $value): int => $carry + $value,
            0
        );

        return $sum % 10 === 0;
    }

    private function zipWith(array $values, array $coefficients): array
    {
        $length = min(count($values), count($coefficients));
        $result = [];

        for ($i = 0; $i < $length; $i++) {
            $result[$i] = $values[$i] * $coefficients[$i];
        }

        return $result;
    }
}
