<?php

namespace App\Traits;

trait FilterableRequest
{
    protected function filterableRules(): array
    {
        return [
            'filters' => 'array'
        ];
    }

    public abstract function filterableFields(): array;

    public function getFilters(): array {
        $filterableFields = $this->filterableFields();
        return array_filter(
            $this->query('filters', []),
            fn($key) => in_array($key, $filterableFields),
            ARRAY_FILTER_USE_KEY
        );
    }
}
