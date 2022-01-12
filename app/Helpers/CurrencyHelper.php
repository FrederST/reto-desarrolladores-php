<?php

namespace App\Helpers;

use App\Models\Currency as ModelsCurrency;
use Illuminate\Support\Facades\Cache;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class CurrencyHelper
{
    private const DEFAULT_CURRENCY_KEY = 'shop.default_currency';

    public static function parseCurrency(string $value, string $currency = null): string
    {
        $currencies = new ISOCurrencies();

        $moneyParser = new DecimalMoneyParser($currencies);

        $money = $moneyParser->parse($value, new Currency($currency ? $currency : config(self::DEFAULT_CURRENCY_KEY)));

        return $money->getAmount();
    }

    public static function toCurrencyFormat(string $value, string $currency = null): string
    {
        $money = new Money($value, new Currency($currency ? $currency : config(self::DEFAULT_CURRENCY_KEY)));
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money);
    }

    public static function getDefaultCurrency(): ModelsCurrency
    {
        return Cache::rememberForever('default_currency', function () {
            return ModelsCurrency::where('alphabetic_code', config('shop.default_currency'))->first();
        });
    }
}
