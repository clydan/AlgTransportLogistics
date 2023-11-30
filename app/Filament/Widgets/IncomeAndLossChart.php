<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;

class IncomeAndLossChart extends ChartWidget
{
    protected static ?string $heading = 'Income and Loss';

    protected static ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        return [
            'datasets' => [
                $this->loss(),
                $this->income(),
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

    private function income(): array
    {
        return [
            'label' => 'Income made',
            'data' => [
                $this->getTotalRecordsCreated(6),
                $this->getTotalRecordsCreated(5),
                $this->getTotalRecordsCreated(4),
                $this->getTotalRecordsCreated(3),
                $this->getTotalRecordsCreated(2),
                $this->getTotalRecordsCreated(1),
                Service::whereDate('updated_at', Carbon::today())->where('status', 'completed')->sum('estimated_cost'),
            ],
            'borderColor' => '#FF6384',
            'backgroundColor' => '#FFB1C1',
        ];
    }

    private function loss(): array
    {
        return [
            'label' => 'Loss made',
            'data' => [
                $this->getTotalRecordsCreated(6),
                $this->getTotalRecordsCreated(5),
                $this->getTotalRecordsCreated(4),
                $this->getTotalRecordsCreated(3),
                $this->getTotalRecordsCreated(2),
                $this->getTotalRecordsCreated(1),
                Service::whereDate('updated_at', Carbon::today())->where('status', 'cancelled')->sum('estimated_cost'),
            ],
            'borderColor' => '#36A2EB',
            'backgroundColor' => '#9BD0F5',
        ];
    }

    private function getTotalRecordsCreated($days): int
    {
        return Service::whereDate('updated_at', Carbon::now()->subDays($days))->where('status', 'cancelled')->count();
    }
}
