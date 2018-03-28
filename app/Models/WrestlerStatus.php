<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WrestlerStatus extends Model
{
    /**
     * Get the available status options.
     *
     * @param  string|null  $current
     * @return \Illuminate\Support\Collection
     */
    public static function getAvailableOptions(string $current = null)
    {
        // These are the default options; if $current is equal to null, this is all
        // that will be returned.
        $options = collect(['Active', 'Inactive']);

        // This part should be self-explanatory, but if you have any questions, just ask.
        switch ($current) {
            case 'Active':
                return $options->merge(['Injured', 'Suspended', 'Retired']);

            case 'Injured':
                return $options->merge(['Injured', 'Retired']);

            case 'Suspended':
                return $options->merge(['Suspended', 'Retired']);

            case 'Retired':
                return $options->merge(['Retired']);
        }

        return $options;
    }

    /**
     * Limit the query to the available options.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string|null  $current
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAvailable($query, $current = null)
    {
        $options = $this->getAvailableOptions($current)->toArray();

        return $query->whereIn('name', $options);
    }
}
