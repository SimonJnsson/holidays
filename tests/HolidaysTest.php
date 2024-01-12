<?php

use Carbon\CarbonImmutable;
use Spatie\Holidays\Actions\Belgium;
use Spatie\Holidays\Actions\Denmark;
use Spatie\Holidays\Enums\Country;
use Spatie\Holidays\Exceptions\HolidaysException;
use Spatie\Holidays\Holidays;

dataset('countries', [
    Country::Belgium->value,
    Country::Denmark->value,
]);

it('can get all holidays of the current year', function (string $country) {
    CarbonImmutable::setTestNow('2024-01-01');

    $holidays = Holidays::new()
        ->country($country)
        ->get();

    expect($holidays)->toMatchSnapshot();
})->with('countries');

it('can get all holidays of 2023', function (string $country) {
    $holidays = Holidays::new()
        ->country($country)
        ->year(2023)
        ->get();

    expect($holidays)->toMatchSnapshot();
})->with('countries');

it('can get all holidays of 2025', function (string $country) {
    $holidays = Holidays::new()
        ->country($country)
        ->year(2025)
        ->get();

    expect($holidays)->toMatchSnapshot();
})->with('countries');

it('can get all holidays of another year and a specific country', function () {
    $holidays = Holidays::new()
        ->year(2023)
        ->country('BE')
        ->get();

    expect($holidays)->toMatchSnapshot();
});

it('cannot get all holidays of an unknown country code', function () {
    Holidays::new()->country('unknown')->get();
})->throws(ValueError::class);

it('cannot get holidays for years before 1970', function () {
    Holidays::new()->year(1969)->get();
})->throws(HolidaysException::class, 'Holidays can only be calculated for years after 1970.');

it('cannot get holidays for years after 2037', function () {
    Holidays::new()->year(2038)->get();
})->throws(HolidaysException::class, 'Holidays can only be calculated for years before 2038');
