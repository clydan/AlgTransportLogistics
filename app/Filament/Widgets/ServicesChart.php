<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;

class ServicesChart extends ChartWidget
{
    protected static ?string $heading = 'Requested Transactions';

    protected static ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        return [
            'datasets' => [
                $this->getDatasetsForCreatedServices(),
            ],
            'labels' => $this->getCustomLabels(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getCustomLabels(): array
    {
        return [
            now()->subDays(6)->diffForHumans(),
            now()->subDays(5)->diffForHumans(),
            now()->subDays(4)->diffForHumans(),
            now()->subDays(3)->diffForHumans(),
            now()->subDays(2)->diffForHumans(),
            now()->subDays(1)->diffForHumans(),
            now()->diffForHumans(),
        ];
    }

    private function getDatasetsForCreatedServices(): array
    {
        return [
            'label' => 'Services Requested',
            'data' => [
                $this->getTotalRecordsCreated(6),
                $this->getTotalRecordsCreated(5),
                $this->getTotalRecordsCreated(4),
                $this->getTotalRecordsCreated(3),
                $this->getTotalRecordsCreated(2),
                $this->getTotalRecordsCreated(1),
                Service::whereDate('updated_at', Carbon::today())->get()->count(),
            ],
        ];
    }

    private function getTotalRecordsCreated($days): int
    {
        return Service::whereDate('updated_at', Carbon::now()->subDays($days))->count();
    }
}
