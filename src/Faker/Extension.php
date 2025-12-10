<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Faker;

use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;

class Extension extends Factory
{
    /** @var list<string> */
    protected static array $extensionProviders = ['FishText'];

    public static function extend(Generator $generator, string $locale = self::DEFAULT_LOCALE): Generator
    {
        foreach (static::$extensionProviders as $provider) {
            $providerClassName = static::getProviderClassname($provider, $locale);
            $generator->addProvider(new $providerClassName($generator));
        }

        return $generator;
    }

    protected static function getProviderClassname($provider, $locale = ''): string
    {
        if ($providerClass = static::findProviderClassname($provider, $locale)) {
            return $providerClass;
        }

        // fallback to default locale
        if ($providerClass = static::findProviderClassname($provider, static::DEFAULT_LOCALE)) {
            return $providerClass;
        }

        // fallback to no locale
        if ($providerClass = static::findProviderClassname($provider)) {
            return $providerClass;
        }

        throw new InvalidArgumentException(sprintf('Unable to find provider "%s" with locale "%s"', $provider, $locale));
    }

    protected static function findProviderClassname($provider, $locale = ''): ?string
    {
        $providerClass = 'TTBooking\\SupportChat\\Faker\\'
            .($locale ? sprintf('Provider\%s\%s', $locale, $provider) : sprintf('Provider\%s', $provider));

        if (class_exists($providerClass)) {
            return $providerClass;
        }

        return null;
    }
}
