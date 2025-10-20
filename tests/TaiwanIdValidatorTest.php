<?php

use Chaoswey\TaiwanIdValidator\TaiwanIdValidator;

describe('isBan', function () {
    it('should only accept 8-digit of string or number', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isBan([]))->toBeFalse();
        expect($validator->isBan(null))->toBeFalse();
        expect($validator->isBan(true))->toBeFalse();
    });

    it('should return true if the input is correct', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isBan(12345676))->toBeTrue()
            ->and($validator->isBan('12345670'))->toBeTrue()
            ->and($validator->isBan('12345671'))->toBeTrue()
            ->and($validator->isBan('12345675'))->toBeTrue()
            ->and($validator->isBan('12345676'))->toBeTrue()
            ->and($validator->isBan('04595257'))->toBeTrue();
    });

    it('should return false if the input is incorrect', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isBan('1234567'))->toBeFalse()
            ->and($validator->isBan(1234567))->toBeFalse()
            ->and($validator->isBan('123456769'))->toBeFalse()
            ->and($validator->isBan(123456769))->toBeFalse()
            ->and($validator->isBan('12345678'))->toBeFalse()
            ->and($validator->isBan('12345672'))->toBeFalse()
            ->and($validator->isBan('04595253'))->toBeFalse();
    });
});

describe('isBan using old format', function () {
    it('should return true if the input is correct', function () {
        $validator = new TaiwanIdValidator();
        $options = ['applyOldRules' => true];

        expect($validator->isBan(12345676, $options))->toBeTrue()
            ->and($validator->isBan('12345675', $options))->toBeTrue()
            ->and($validator->isBan('12345676', $options))->toBeTrue()
            ->and($validator->isBan('04595257', $options))->toBeTrue();
    });

    it('should return false if the input is incorrect', function () {
        $validator = new TaiwanIdValidator();
        $options = ['applyOldRules' => true];

        expect($validator->isBan('1234567', $options))->toBeFalse()
            ->and($validator->isBan(1234567, $options))->toBeFalse()
            ->and($validator->isBan('123456769', $options))->toBeFalse()
            ->and($validator->isBan(123456769, $options))->toBeFalse()
            ->and($validator->isBan('12345678', $options))->toBeFalse()
            ->and($validator->isBan('12345670', $options))->toBeFalse()
            ->and($validator->isBan('12345671', $options))->toBeFalse()
            ->and($validator->isBan('04595252', $options))->toBeFalse();
    });
});

describe('isCitizenDigitalCertificate', function () {
    it('should only accept strings with length 16', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isCitizenDigitalCertificate([]))->toBeFalse()
            ->and($validator->isCitizenDigitalCertificate(''))->toBeFalse()
            ->and($validator->isCitizenDigitalCertificate('AB123456789012345'))->toBeFalse()
            ->and($validator->isCitizenDigitalCertificate('AB1234567890123'))->toBeFalse();
    });

    it('should return true if the input is correct', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isCitizenDigitalCertificate('AB12345678901234'))->toBeTrue()
            ->and($validator->isCitizenDigitalCertificate('RP47809425348791'))->toBeTrue();
    });

    it('should return false if the input is incorrect', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isCitizenDigitalCertificate('ab12345678901234'))->toBeFalse()
            ->and($validator->isCitizenDigitalCertificate('A112345678901234'))->toBeFalse()
            ->and($validator->isCitizenDigitalCertificate('9B12345678901234'))->toBeFalse()
            ->and($validator->isCitizenDigitalCertificate('AA123456789012J4'))->toBeFalse();
    });
});

describe('isDonationCode', function () {
    it('should only accept strings with length 3-7', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isDonationCode([]))->toBeFalse()
            ->and($validator->isDonationCode(null))->toBeFalse()
            ->and($validator->isDonationCode('00'))->toBeFalse()
            ->and($validator->isDonationCode('12345678'))->toBeFalse()
            ->and($validator->isDonationCode(12345678))->toBeFalse()
            ->and($validator->isDonationCode('ab3456'))->toBeFalse();
    });

    it('should return true if the input is correct', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isDonationCode('001'))->toBeTrue()
            ->and($validator->isDonationCode('10001'))->toBeTrue()
            ->and($validator->isDonationCode('2134567'))->toBeTrue()
            ->and($validator->isDonationCode(123))->toBeTrue()
            ->and($validator->isDonationCode(10001))->toBeTrue()
            ->and($validator->isDonationCode(2134567))->toBeTrue();
    });
});

describe('isMobileBarcode', function () {
    it('should only accept strings with length 8', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isMobileBarcode([]))->toBeFalse()
            ->and($validator->isMobileBarcode(''))->toBeFalse()
            ->and($validator->isMobileBarcode('/ABCD1234'))->toBeFalse()
            ->and($validator->isMobileBarcode('/ABCD12'))->toBeFalse();
    });

    it('should return false if the input contains invalid char', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isMobileBarcode('/ABCD12;'))->toBeFalse()
            ->and($validator->isMobileBarcode('/ABCD$12'))->toBeFalse()
            ->and($validator->isMobileBarcode('/ab12345'))->toBeFalse();
    });

    it('should return true if the input is correct', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isMobileBarcode('/+.-++..'))->toBeTrue()
            ->and($validator->isMobileBarcode('/AAA33AA'))->toBeTrue()
            ->and($validator->isMobileBarcode('/P4SV.-I'))->toBeTrue()
            ->and($validator->isMobileBarcode('/O0O01I1'))->toBeTrue();
    });
});

describe('isIdCardNumber', function () {
    describe('National ID tests', function () {
        it('should validate a correct national ID number', function () {
            $validator = new TaiwanIdValidator();
            $options = [
                'nationalId' => true,
                'uiNumber' => false,
            ];

            expect($validator->isIdCardNumber('A123456789', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('F131104093', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('O158238845', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('N116247806', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('L122544270', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('C180661564', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('Y123456788', $options))->toBeTrue();
        });

        it('should invalidate an incorrect national ID number', function () {
            $validator = new TaiwanIdValidator();
            $options = [
                'nationalId' => true,
                'uiNumber' => false,
            ];

            expect($validator->isIdCardNumber('A12345678', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('a123456789', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('A123456788', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('F131104091', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('O158238842', $options))->toBeFalse();
        });

        it('should invalidate a national ID number when nationalId option is false', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A123456789', [
                'nationalId' => false,
                'uiNumber' => false,
            ]))->toBeFalse();
        });
    });

    describe('Old format UI number tests', function () {
        it('should validate a correct old format UI number', function () {
            $validator = new TaiwanIdValidator();
            $options = [
                'nationalId' => false,
                'uiNumber' => [
                    'newFormat' => false,
                    'oldFormat' => true,
                ],
            ];

            expect($validator->isIdCardNumber('AB23456789', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('AA00000009', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('AB00207171', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('AC03095424', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('BD01300667', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('CC00151114', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('HD02717288', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('TD00251124', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('AD30196818', $options))->toBeTrue();
        });

        it('should invalidate an incorrect old format UI number', function () {
            $validator = new TaiwanIdValidator();
            $options = [
                'nationalId' => false,
                'uiNumber' => [
                    'newFormat' => false,
                    'oldFormat' => true,
                ],
            ];

            expect($validator->isIdCardNumber('AA1234567', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('aa00000009', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('AA00000000', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('FG31104091', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('OY58238842', $options))->toBeFalse();
        });

        it('should only accept strings begin with 2 English letters where the second letter is in [A-D]', function () {
            $validator = new TaiwanIdValidator();
            $options = [
                'nationalId' => false,
                'uiNumber' => [
                    'newFormat' => false,
                    'oldFormat' => true,
                ],
            ];

            expect($validator->isIdCardNumber('2123456789', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('1A23456789', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('A123456789', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('AE23456785', $options))->toBeFalse();
        });
    });

    describe('New format UI number tests', function () {
        it('should only accept strings begin with 1 English letters', function () {
            $validator = new TaiwanIdValidator();
            $options = [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => true,
                ],
            ];

            expect($validator->isIdCardNumber('2123456789', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('1A23456789', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('AA23456789', $options))->toBeFalse();
        });

        it('should invalidate a UI number when the first number is not 8 or 9', function () {
            $validator = new TaiwanIdValidator();
            $options = [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => true,
                ],
            ];

            expect($validator->isIdCardNumber('A323456789', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('A423456789', $options))->toBeFalse();
        });

        it('should validate a correct new format UI number', function () {
            $validator = new TaiwanIdValidator();
            $options = [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => true,
                ],
            ];

            expect($validator->isIdCardNumber('A800000014', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('A900207177', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('A803095426', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('B801300667', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('C800151116', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('H802717288', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('T900251126', $options))->toBeTrue()
                ->and($validator->isIdCardNumber('A930196810', $options))->toBeTrue();
        });

        it('should invalidate an incorrect new format UI number', function () {
            $validator = new TaiwanIdValidator();
            $options = [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => true,
                ],
            ];

            expect($validator->isIdCardNumber('a800000009', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('A800000000', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('F931104091', $options))->toBeFalse()
                ->and($validator->isIdCardNumber('O958238842', $options))->toBeFalse();
        });

        it('should validate a correct new format UI number for foreign or stateless resident', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A800000014', [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => [
                        'foreignOrStateless' => true,
                        'statelessResident' => false,
                        'hkMacaoResident' => false,
                        'mainlandChinaResident' => false,
                    ],
                ],
            ]))->toBeTrue();
        });

        it('should validate a correct new format UI number for stateless resident', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A870000015', [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => [
                        'foreignOrStateless' => false,
                        'statelessResident' => true,
                        'hkMacaoResident' => false,
                        'mainlandChinaResident' => false,
                    ],
                ],
            ]))->toBeTrue();
        });

        it('should validate a correct new format UI number for HK/Macao resident', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A880000018', [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => [
                        'foreignOrStateless' => false,
                        'statelessResident' => false,
                        'hkMacaoResident' => true,
                        'mainlandChinaResident' => false,
                    ],
                ],
            ]))->toBeTrue();
        });

        it('should validate a correct new format UI number for mainland China resident', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A890000011', [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => [
                        'foreignOrStateless' => false,
                        'statelessResident' => false,
                        'hkMacaoResident' => false,
                        'mainlandChinaResident' => true,
                    ],
                ],
            ]))->toBeTrue();
        });

        it('should invalidate an incorrect new format UI number when all categories disabled', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A800000014', [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => [
                        'foreignOrStateless' => false,
                        'statelessResident' => false,
                        'hkMacaoResident' => false,
                        'mainlandChinaResident' => false,
                    ],
                ],
            ]))->toBeFalse();
        });

        it('should invalidate a malformed new format UI number', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A8923456', [
                'nationalId' => false,
                'uiNumber' => [
                    'oldFormat' => false,
                    'newFormat' => true,
                ],
            ]))->toBeFalse();
        });
    });

    describe('Default options tests', function () {
        it('should validate a national ID number by default', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A123456789'))->toBeTrue();
        });

        it('should invalidate an ID number with an incorrect format and default options', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A123456780'))->toBeFalse();
        });

        it('should validate an old format UI number by default', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('AB23456789'))->toBeTrue();
        });

        it('should validate a new format UI number for foreign or stateless resident by default', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A800000014'))->toBeTrue();
        });

        it('should validate a new format UI number for stateless resident by default', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A870000015'))->toBeTrue();
        });

        it('should validate a new format UI number for HK/Macao resident by default', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A880000018'))->toBeTrue();
        });

        it('should validate a new format UI number for mainland China resident by default', function () {
            $validator = new TaiwanIdValidator();

            expect($validator->isIdCardNumber('A890000011'))->toBeTrue();
        });
    });

    it('should invalidate an ID number with an incorrect format', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isIdCardNumber('1234567890'))->toBeFalse();
    });

    it('should invalidate an empty string', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isIdCardNumber(''))->toBeFalse();
    });

    it('should invalidate a non-string input', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isIdCardNumber(123456789))->toBeFalse();
    });

    it('should only accept strings with length 10', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isIdCardNumber([]))->toBeFalse()
            ->and($validator->isIdCardNumber('A1234567899'))->toBeFalse()
            ->and($validator->isIdCardNumber('A12345678'))->toBeFalse();
    });

    it('should only accept strings begin with English letter', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isIdCardNumber('2123456789'))->toBeFalse()
            ->and($validator->isIdCardNumber('1123456789'))->toBeFalse();
    });

    it('should return false if the first number is not in [1, 2, 8, 9]', function () {
        $validator = new TaiwanIdValidator();

        expect($validator->isIdCardNumber('A323456789'))->toBeFalse()
            ->and($validator->isIdCardNumber('A423456789'))->toBeFalse()
            ->and($validator->isIdCardNumber('A523456789'))->toBeFalse()
            ->and($validator->isIdCardNumber('A623456789'))->toBeFalse()
            ->and($validator->isIdCardNumber('A723456789'))->toBeFalse();
    });
});
