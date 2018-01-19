<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WrestlerStatus extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 2;
    const INJURED = 3;
    const SUSPENDED = 4;
    const RETIRED = 5;

    public static function available($current_status = null, $map = true)
    {
        $options = collect([self::ACTIVE, self::INACTIVE]);

        if ($current_status != null) {
            if ($current_status == self::ACTIVE) {
                $options = $options->merge([self::INJURED, self::SUSPENDED, self::RETIRED]);
            } elseif ($current_status == self::INACTIVE) {
                $options = $options->merge([self::RETIRED]);
            } elseif ($current_status == self::INJURED) {
                $options = $options->merge([self::INJURED, self::RETIRED]);
            } elseif ($current_status == self::SUSPENDED) {
                $options = $options->merge([self::SUSPENDED, self::RETIRED]);
            } else {
                $options = $options->merge([self::RETIRED]);
            }
        }

        if ($map) {
            return $options->map(function ($option) {
                return WrestlerStatus::find($option);
            });
        }

        return $options;
    }
}
