<?php

namespace App\Helpers;

class ChartHelper
{
    /**
     * Get a color for chart based on index
     *
     * @param int $index
     * @return string
     */
    public static function getChartColor($index)
    {
        $colors = [
            '#4e73df', // Primary
            '#1cc88a', // Success
            '#36b9cc', // Info
            '#f6c23e', // Warning
            '#e74a3b', // Danger
            '#6f42c1', // Purple
            '#fd7e14', // Orange
            '#20c9a6', // Teal
            '#5a5c69', // Gray
            '#858796'  // Secondary
        ];
        
        return $colors[$index % count($colors)];
    }
} 