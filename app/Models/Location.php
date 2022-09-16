<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \App\Models\Location|\Illuminate\Database\Eloquent\Builder within(float $radius)
 * @method static \App\Models\Location|\Illuminate\Database\Eloquent\Builder kilometersOf(float $latitude, float $longitude)
 * @method static \App\Models\Location|\Illuminate\Database\Eloquent\Builder closestFirst()
 *
 * @package App\Models
 *
 * @author  Peter Cortez <innov.petercortez@gmail.com>
 */
class Location extends Model
{
    use HasFactory;

    /**
     * Adds a `distance` attribute to the returned record whose value is equal to the
     * distance of a location (in kilometers) from the given coordinates.
     *
     * @link https://gis.stackexchange.com/a/66673 For more information on how the
     *       distance is calculated.
     * @link https://www.distance.to/ to verify the calculated distance
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float                                 $latitude
     * @param float                                 $longitude
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKilometersOf(
        Builder $query,
        float $latitude,
        float $longitude
    ): Builder {
        $distance = "(
            6371
            * acos(
                cos(radians({$latitude}))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians({$longitude}))
                + sin(radians({$latitude}))
                * sin(radians(latitude))
            )
        )";

        return $query
            ->select('*')
            ->selectSub($distance, 'distance');
    }

    /**
     * Filters out the records whose `distance` falls within the given unit.
     *
     * Note: This must be used within {@see static::scopeKilometersOf()} for the
     * query not to break.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float                                 $distance
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithin(Builder $query, float $distance): Builder
    {
        return $query->having('distance', '<=', $distance);
    }

    /**
     * Orders the records by their `distance` in ascending order
     *
     * Note: This must be used within {@see static::scopeKilometersOf()} for the
     * query not to break.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosestFirst(Builder $query): Builder
    {
        return $query->orderBy('distance');
    }
}
