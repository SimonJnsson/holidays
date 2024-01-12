<?php

namespace Spatie\Holidays\Actions;

use Carbon\CarbonImmutable;
use Spatie\Holidays\Exceptions\HolidaysException;

class Denmark implements Executable
{
    public function execute(int $year): array
    {
        $this->ensureYearCanBeCalculated($year);

        $fixedHolidays = $this->fixedHolidays($year);
        $variableHolidays = $this->variableHolidays($year);

        return array_merge($fixedHolidays, $variableHolidays);
    }

    protected function ensureYearCanBeCalculated(int $year): void
    {
        if ($year < 1970) {
            throw HolidaysException::yearTooLow();
        }

        if ($year > 2037) {
            throw HolidaysException::yearTooHigh();
        }
    }

    /** @return array<string, CarbonImmutable> */
    protected function fixedHolidays(int $year): array
    {
        $dates = [
            'Nytårsdag' => '01-01',
            'Juledag' => '25-12',
            '2. Juledag' => '26-12',
        ];

        foreach ($dates as $name => $date) {
            $dates[$name] = CarbonImmutable::createFromFormat('d-m-Y', "{$date}-{$year}");
        }

        return $dates;
    }

    /** @return array<string, CarbonImmutable> */
    protected function variableHolidays(int $year): array
    {
        $easter = CarbonImmutable::createFromTimestamp(easter_date($year))
            ->setTimezone('Europe/Copenhagen');

        return [
            'Skærtorsdag' => $easter->subDays(3),
            'Langfredag' => $easter->subDays(2),
            'Påskedag' => $easter,
            '2. Påskedag' => $easter->addDay(),
            'Kristi himmelfartsdag' => $easter->addDays(39),
            'Pinsedag' => $easter->addDays(49),
            '2. Pinsedag' => $easter->addDays(50),
        ];
    }
}
