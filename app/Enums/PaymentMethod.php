<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case CreditCard = 'credit_card';

    case BankTransfer = 'bank_transfer';

    case Paypal = 'paypal';

    case ApplePay = 'apple_pay';

    case GooglePay = 'google_pay';

    public function getLabel(): string
    {
        return match ($this) {
            self::CreditCard => 'Credit Card',
            self::BankTransfer => 'Bank Transfer',
            self::Paypal => 'PayPal',
            self::ApplePay => 'Apple Pay',
            self::GooglePay => 'Google Pay',
        };
    }
}
