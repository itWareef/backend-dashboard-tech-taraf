<?php
namespace App\Core\Statistics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Spatie\QueryBuilder\QueryBuilder;

abstract class AbstractStatisticsRowsCounted
{
    /**
     * Get the model query builder.
     *
     * @return Builder
     */
    abstract protected function getModelQuery(): string;

    /**
     * Column to filter dates by.
     *
     * @return string
     */
    protected function getDateColumn(): string
    {
        return 'created_at'; // Default column, can be overridden
    }

    /**
     * Get the column to sum.
     *
     * @return string|null
     */
    protected function getSumColumn(): ?string
    {
        return null; // By default, no sum column. Override in subclass.
    }

    /**
     * Get statistics grouped by a specific interval within a date range.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $query = $this->getModelQuery();
        $dateColumn = $this->getDateColumn();
        $sumColumn = $this->getSumColumn();
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();
        $interval = $this->getInterval();

        // Determine the date format for grouping
        $dateFormat = match ($interval) {
            'days' => '%Y-%m-%d',
            'months' => '%Y-%m',
            'years' => '%Y',
            default => throw new InvalidArgumentException('Invalid interval specified'),
        };

        $selectRaw = "DATE_FORMAT({$dateColumn}, '{$dateFormat}') as date_group";
        $selectRaw .= $sumColumn ? ", SUM({$sumColumn}) as total_sum" : ", COUNT(*) as total_count";

        $data = QueryBuilder::for($query)
            ->scopes($this->getScopes())
            ->allowedFilters($this->filters())
            ->whereBetween(DB::raw("DATE({$dateColumn})"), [$startDate, $endDate])
            ->selectRaw($selectRaw)
            ->groupBy('date_group')
            ->orderBy('date_group')
            ->get();

        // Initialize an array for all possible intervals
        $intervals = $this->initializeIntervals($startDate, $endDate, $interval);

        // Populate the array with the sum or count
        foreach ($data as $item) {
            $intervals[$item->date_group] = $sumColumn ? $item->total_sum : $item->total_count;
        }

        return $intervals;
    }

    /**
     * Initialize an array with all possible intervals set to 0.
     */
    protected function initializeIntervals(string $startDate, string $endDate, string $interval): array
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $intervals = [];

        while ($start <= $end) {
            $key = match ($interval) {
                'days' => $start->format('Y-m-d'),
                'months' => $start->format('Y-m'),
                'years' => $start->format('Y'),
            };
            $intervals[$key] = 0;

            $start->modify(match ($interval) {
                'days' => '+1 day',
                'months' => '+1 month',
                'years' => '+1 year',
            });
        }

        return $intervals;
    }

    private function getInterval()
    {
        return $this->getRequestKey()['period_type'] ?? 'months';
    }

    private function getRequestKey()
    {
        return request();
    }

    private function getStartDate(): string
    {
        return $this->getRequestKey()['from_date'] ?? now()->subMonths(6)->startOfMonth()->toDateString();
    }

    public function getScopes(): array
    {
        return [];
    }

    private function getEndDate(): string
    {
        return $this->getRequestKey()['to_date'] ?? now()->toDateString();
    }

    protected function filters():array
    {
        return [];
    }
}
