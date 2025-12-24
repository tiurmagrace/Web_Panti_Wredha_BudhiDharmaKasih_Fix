<?php
/**
 * =================================================================
 * FILE: app/Traits/HasFilters.php
 * =================================================================
 */

namespace App\Traits;

trait HasFilters
{
    /**
     * Apply filters to query
     */
    public function scopeApplyFilters($query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if (empty($value)) {
                continue;
            }

            // Handle search
            if ($key === 'search' && method_exists($this, 'scopeSearch')) {
                $query->search($value);
                continue;
            }

            // Handle date range
            if ($key === 'date_from') {
                $query->where('created_at', '>=', $value);
                continue;
            }

            if ($key === 'date_to') {
                $query->where('created_at', '<=', $value);
                continue;
            }

            // Handle regular filters
            if (in_array($key, $this->getFillable())) {
                $query->where($key, $value);
            }
        }

        return $query;
    }
}
