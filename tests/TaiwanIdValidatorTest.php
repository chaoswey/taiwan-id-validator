<?php

namespace Chaoswey\TaiwanIdValidator\Tests;

use Chaoswey\TaiwanIdValidator\TaiwanIdValidator;
use PHPUnit\Framework\TestCase;

class TaiwanIdValidatorTest extends TestCase
{
    public function testIsGuiNumberValid()
    {
        $valid = new TaiwanIdValidator();

        $this->assertFalse($valid->isGuiNumberValid(129001231111));
        $this->assertFalse($valid->isGuiNumberValid('99900011111'));

        //should return true if the input is correct

        $this->assertTrue($valid->isGuiNumberValid(12345676));
        $this->assertTrue($valid->isGuiNumberValid('12345675'));
        $this->assertTrue($valid->isGuiNumberValid('12345676')); // 6th char is 7
        $this->assertTrue($valid->isGuiNumberValid('04595257'));

        //should return false if the input is incorrect
        $this->assertFalse($valid->isGuiNumberValid('1234567'));
        $this->assertFalse($valid->isGuiNumberValid(1234567));
        $this->assertFalse($valid->isGuiNumberValid('123456769'));
        $this->assertFalse($valid->isGuiNumberValid(123456769));
        $this->assertFalse($valid->isGuiNumberValid('12345678'));
        $this->assertFalse($valid->isGuiNumberValid('12345670'));
        $this->assertFalse($valid->isGuiNumberValid('04595252'));

        //isGuiNumValid extended format
        $this->assertTrue($valid->isGuiNumberValid(12345676, true));
        $this->assertTrue($valid->isGuiNumberValid('12345675', true));
        $this->assertTrue($valid->isGuiNumberValid('12345676', true)); // 6th char is 7
        $this->assertTrue($valid->isGuiNumberValid('12345670', true));
        $this->assertTrue($valid->isGuiNumberValid('04595257', true));
        $this->assertTrue($valid->isGuiNumberValid('04595252', true));

        //should return false if the input is incorrect
        $this->assertFalse($valid->isGuiNumberValid(1234567, true));
        $this->assertFalse($valid->isGuiNumberValid(123456769, true));
        $this->assertFalse($valid->isGuiNumberValid('12345678', true));
        $this->assertFalse($valid->isGuiNumberValid('123456769', true));
        $this->assertFalse($valid->isGuiNumberValid('1234567', true));
    }

    public function testIsNationalIdentificationNumberValid()
    {
        $valid = new TaiwanIdValidator();
        //should only accept strings with length 10
        $this->assertFalse($valid->isNationalIdentificationNumberValid(129001231111));
        $this->assertFalse($valid->isNationalIdentificationNumberValid('A1234567899'));
        $this->assertFalse($valid->isNationalIdentificationNumberValid('A12345678'));

        //should only accept strings Begin with English letter
        $this->assertFalse($valid->isNationalIdentificationNumberValid('2123456789'));
        $this->assertFalse($valid->isNationalIdentificationNumberValid('1123456789'));

        //should return false if the first number is not 1 or 2
        $this->assertFalse($valid->isNationalIdentificationNumberValid('A323456789'));
        $this->assertFalse($valid->isNationalIdentificationNumberValid('A423456789'));

        //should return true if the input is correct
        $this->assertTrue($valid->isNationalIdentificationNumberValid('A123456789'));
        $this->assertTrue($valid->isNationalIdentificationNumberValid('F131104093'));
        $this->assertTrue($valid->isNationalIdentificationNumberValid('O158238845'));
        $this->assertTrue($valid->isNationalIdentificationNumberValid('N116247806'));
        $this->assertTrue($valid->isNationalIdentificationNumberValid('L122544270'));
        $this->assertTrue($valid->isNationalIdentificationNumberValid('C180661564'));
        $this->assertTrue($valid->isNationalIdentificationNumberValid('Y123456788'));

        //should return false if the input is incorrect
        $this->assertFalse($valid->isNationalIdentificationNumberValid('a123456789'));
        $this->assertFalse($valid->isNationalIdentificationNumberValid('A123456788'));
        $this->assertFalse($valid->isNationalIdentificationNumberValid('F131104091'));
        $this->assertFalse($valid->isNationalIdentificationNumberValid('O158238842'));
    }

    public function testIsNewResidentCertificateNumberValid()
    {
        $valid = new TaiwanIdValidator();
        //should only accept strings with length 10
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('AA234567899'));
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('AA2345678'));

        //should only accept strings Begin with 1 English letters
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('2123456789'));
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('1A23456789'));
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('AA23456789'));

        //should return false if the first number is not 8 or 9
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('A323456789'));
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('A423456789'));

        //should return true if the input is correct
        $this->assertTrue($valid->isNewResidentCertificateNumberValid('A800000014'));
        $this->assertTrue($valid->isNewResidentCertificateNumberValid('A900207177'));
        $this->assertTrue($valid->isNewResidentCertificateNumberValid('A803095426'));
        $this->assertTrue($valid->isNewResidentCertificateNumberValid('B801300667'));
        $this->assertTrue($valid->isNewResidentCertificateNumberValid('C800151116'));
        $this->assertTrue($valid->isNewResidentCertificateNumberValid('H802717288'));
        $this->assertTrue($valid->isNewResidentCertificateNumberValid('T900251126'));
        $this->assertTrue($valid->isNewResidentCertificateNumberValid('A930196810'));

        //should return false if the input is incorrect
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('a800000009'));
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('A800000000'));
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('F931104091'));
        $this->assertFalse($valid->isNewResidentCertificateNumberValid('O958238842'));
    }

    public function testIsOriginalResidentCertificateNumberValid()
    {
        $valid = new TaiwanIdValidator();
        //should only accept strings with length 10
        $this->assertFalse($valid->isOriginalResidentCertificateNumberValid('AA234567899'));
        $this->assertFalse($valid->isOriginalResidentCertificateNumberValid('AA2345678'));

        //should only accept strings Begin with 2 English letters
        $this->assertFalse($valid->isOriginalResidentCertificateNumberValid('2123456789'));
        $this->assertFalse($valid->isOriginalResidentCertificateNumberValid('1A23456789'));
        $this->assertFalse($valid->isOriginalResidentCertificateNumberValid('A123456789'));

        //should return true if the input is correct
        $this->assertTrue($valid->isOriginalResidentCertificateNumberValid('AA00000009'));
        $this->assertTrue($valid->isOriginalResidentCertificateNumberValid('AB00207171'));
        $this->assertTrue($valid->isOriginalResidentCertificateNumberValid('AC03095424'));
        $this->assertTrue($valid->isOriginalResidentCertificateNumberValid('BD01300667'));
        $this->assertTrue($valid->isOriginalResidentCertificateNumberValid('CC00151114'));
        $this->assertTrue($valid->isOriginalResidentCertificateNumberValid('HD02717288'));
        $this->assertTrue($valid->isOriginalResidentCertificateNumberValid('TD00251124'));
        $this->assertTrue($valid->isOriginalResidentCertificateNumberValid('AD30196818'));

        //should return false if the input is incorrect
        $this->assertFalse($valid->isOriginalResidentCertificateNumberValid('aa00000009'));
        $this->assertFalse($valid->isOriginalResidentCertificateNumberValid('AA00000000'));
        $this->assertFalse($valid->isOriginalResidentCertificateNumberValid('FG31104091'));
        $this->assertFalse($valid->isOriginalResidentCertificateNumberValid('OY58238842'));
    }

    public function testIsResidentCertificateNumberValid()
    {
        $valid = new TaiwanIdValidator();
        //should only accept strings with length 10
        $this->assertFalse($valid->isResidentCertificateNumberValid('AA234567899'));
        $this->assertFalse($valid->isResidentCertificateNumberValid('AA2345678'));

        //should only accept strings Begin with 2 English letters
        $this->assertFalse($valid->isResidentCertificateNumberValid('2123456789'));
        $this->assertFalse($valid->isResidentCertificateNumberValid('1A23456789'));
        $this->assertFalse($valid->isResidentCertificateNumberValid('A123456789'));

        //should return true if the input is correct
        $this->assertTrue($valid->isResidentCertificateNumberValid('AA00000009'));
        $this->assertTrue($valid->isResidentCertificateNumberValid('AB00207171'));
        $this->assertTrue($valid->isResidentCertificateNumberValid('AC03095424'));
        $this->assertTrue($valid->isResidentCertificateNumberValid('BD01300667'));
        $this->assertTrue($valid->isResidentCertificateNumberValid('CC00151114'));
        $this->assertTrue($valid->isResidentCertificateNumberValid('HD02717288'));
        $this->assertTrue($valid->isResidentCertificateNumberValid('TD00251124'));
        $this->assertTrue($valid->isResidentCertificateNumberValid('AD30196818'));

        //should return false if the input is incorrect
        $this->assertFalse($valid->isResidentCertificateNumberValid('aa00000009'));
        $this->assertFalse($valid->isResidentCertificateNumberValid('AA00000000'));
        $this->assertFalse($valid->isResidentCertificateNumberValid('FG31104091'));
        $this->assertFalse($valid->isResidentCertificateNumberValid('OY58238842'));
    }

    public function testIsCitizenDigitalCertificateNumberValid()
    {
        $valid = new TaiwanIdValidator();
        //should only accept strings with length 16
        $this->assertFalse($valid->isCitizenDigitalCertificateNumberValid('AB123456789012345'));
        $this->assertFalse($valid->isCitizenDigitalCertificateNumberValid('AB1234567890123'));

        //should return true if the input is correct
        $this->assertTrue($valid->isCitizenDigitalCertificateNumberValid('AB12345678901234'));
        $this->assertTrue($valid->isCitizenDigitalCertificateNumberValid('RP47809425348791'));

        //should return false if the input is incorrect
        $this->assertFalse($valid->isCitizenDigitalCertificateNumberValid('ab12345678901234'));
        $this->assertFalse($valid->isCitizenDigitalCertificateNumberValid('A112345678901234'));
        $this->assertFalse($valid->isCitizenDigitalCertificateNumberValid('9B12345678901234'));
        $this->assertFalse($valid->isCitizenDigitalCertificateNumberValid('AA123456789012J4'));
    }

    public function testIsEInvoiceCellPhoneBarcodeValid()
    {
        $valid = new TaiwanIdValidator();
        //should only accept strings with length 8
        $this->assertFalse($valid->isEInvoiceCellPhoneBarcodeValid('/ABCD1234'));
        $this->assertFalse($valid->isEInvoiceCellPhoneBarcodeValid('/ABCD12'));

        //should return false if the input contains invalid char
        $this->assertFalse($valid->isEInvoiceCellPhoneBarcodeValid('/ABCD12;'));
        $this->assertFalse($valid->isEInvoiceCellPhoneBarcodeValid('/ABCD$12'));
        $this->assertFalse($valid->isEInvoiceCellPhoneBarcodeValid('/ab12345'));

        //should return true if the input is correct
        $this->assertTrue($valid->isEInvoiceCellPhoneBarcodeValid('/+.-++..'));
        $this->assertTrue($valid->isEInvoiceCellPhoneBarcodeValid('/AAA33AA'));
        $this->assertTrue($valid->isEInvoiceCellPhoneBarcodeValid('/P4SV.-I'));
        $this->assertTrue($valid->isEInvoiceCellPhoneBarcodeValid('/O0O01I1'));
    }

    public function testIsEInvoiceDonateCodeValid()
    {
        $valid = new TaiwanIdValidator();
        //should only accept strings with length 3-7
        $this->assertFalse($valid->isEInvoiceDonateCodeValid('00'));
        $this->assertFalse($valid->isEInvoiceDonateCodeValid('12345678'));
        $this->assertFalse($valid->isEInvoiceDonateCodeValid(12345678));
        $this->assertFalse($valid->isEInvoiceDonateCodeValid('ab3456'));

        //should return false if the input is incorrect
        $this->assertTrue($valid->isEInvoiceDonateCodeValid('001'));
        $this->assertTrue($valid->isEInvoiceDonateCodeValid('10001'));
        $this->assertTrue($valid->isEInvoiceDonateCodeValid('2134567'));
        $this->assertTrue($valid->isEInvoiceDonateCodeValid(123));
        $this->assertTrue($valid->isEInvoiceDonateCodeValid(10001));
        $this->assertTrue($valid->isEInvoiceDonateCodeValid(2134567));
    }

    public function testIsCreditCardNumberValid()
    {
        $valid = new TaiwanIdValidator();

        //should only accept strings with length 12 ~ 19
        $this->assertFalse($valid->isCreditCardNumberValid('1234567890'));
        $this->assertFalse($valid->isCreditCardNumberValid('12345678901234567890'));

        //should return false if the input contains invalid char
        $this->assertFalse($valid->isCreditCardNumberValid('123456789012345a'));
        $this->assertFalse($valid->isCreditCardNumberValid('123456789012345;'));
        $this->assertFalse($valid->isCreditCardNumberValid('123456789012345$'));

        //should return true if the input card number belongs to American Express
        $this->assertTrue($valid->isCreditCardNumberValid('348282246310002', ['checkIssuerRegexes'=> true]));
        $this->assertTrue($valid->isCreditCardNumberValid('371449635398431', ['checkIssuerRegexes'=> true]));

        //should return true if the input card number belongs to Diners Club
        $this->assertTrue($valid->isCreditCardNumberValid('30569309025904', ['checkIssuerRegexes'=> true]));
        $this->assertTrue($valid->isCreditCardNumberValid('38520000023237', ['checkIssuerRegexes'=> true]));

        //should return true if the input card number belongs to Discover
        $this->assertTrue($valid->isCreditCardNumberValid('6011111111111117', ['checkIssuerRegexes'=> true]));
        $this->assertTrue($valid->isCreditCardNumberValid('6011000990139424', ['checkIssuerRegexes'=> true]));

        //should return true if the input card number belongs to JCB
        $this->assertTrue($valid->isCreditCardNumberValid('3530111333300000', ['checkIssuerRegexes'=> true]));
        $this->assertTrue($valid->isCreditCardNumberValid('3566002020360505', ['checkIssuerRegexes'=> true]));

        //should return true if the input card number belongs to MasterCard
        $this->assertTrue($valid->isCreditCardNumberValid('5555555555554444', ['checkIssuerRegexes'=> true]));
        $this->assertTrue($valid->isCreditCardNumberValid('5105105105105100', ['checkIssuerRegexes'=> true]));

        //should return true if the input card number belongs to Visa
        $this->assertTrue($valid->isCreditCardNumberValid('4111111111111111', ['checkIssuerRegexes'=> true]));
        $this->assertTrue($valid->isCreditCardNumberValid('4012888888881881', ['checkIssuerRegexes'=> true]));

        //should return true if the input card number belongs to UnionPay
        $this->assertTrue($valid->isCreditCardNumberValid('6221260000000000', ['checkIssuerRegexes'=> true]));
        $this->assertTrue($valid->isCreditCardNumberValid('6221260000000091', ['checkIssuerRegexes'=> true]));

        //should return true if the input card number belongs to Maestro
        $this->assertTrue($valid->isCreditCardNumberValid('6759649826438453', ['checkIssuerRegexes'=> true]));
        $this->assertTrue($valid->isCreditCardNumberValid('6759649826438461', ['checkIssuerRegexes'=> true]));

        //should return true if the input card number belongs to Switch
        $this->assertTrue($valid->isCreditCardNumberValid('6331101999990016', ['checkIssuerRegexes'=> true]));
        $this->assertTrue($valid->isCreditCardNumberValid('6331101999990024', ['checkIssuerRegexes'=> true]));

        //should return false if the input card number does not belong to any issuer
        $this->assertFalse($valid->isCreditCardNumberValid('1234567890123456', ['checkIssuerRegexes'=> true]));
        $this->assertFalse($valid->isCreditCardNumberValid('1234567890123464', ['checkIssuerRegexes'=> true]));

        //should return true if checkIssuerRegexes is false
        $this->assertTrue($valid->isCreditCardNumberValid('1234567890123452', ['checkIssuerRegexes'=> false]));
        $this->assertTrue($valid->isCreditCardNumberValid('0123456789012347'));

        //should return false if the input card number is invalid
        $this->assertFalse($valid->isCreditCardNumberValid('1234567890123456'));
        $this->assertFalse($valid->isCreditCardNumberValid('0123456789012345'));
    }
}
