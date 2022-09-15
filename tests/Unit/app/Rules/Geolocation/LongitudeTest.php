<?php

namespace Tests\Unit\app\Rules\Geolocation;

use App\Rules\Geolocation\Longitude;
use Tests\TestCase;
use function array_map;
use function range;

class LongitudeTest extends TestCase
{
    /**
     * @var \App\Rules\Geolocation\Longitude
     */
    protected $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = app(Longitude::class);
    }

    /**
     * Verifies that the rule returns false when non-numeric values are passed to it
     *
     * @param $invalidValue
     *
     * @return void
     *
     * @dataProvider nonNumericLongitudes
     */
    public function testRule_whenValueIsNonNumeric_shouldReturnFalse($invalidValue)
    {
        $result = $this->rule->passes('attribute', $invalidValue);

        $this->assertFalse($result);
    }

    /**
     * Verifies that the rule fails when the longitude is less than -180
     *
     * @param $lessThanNegativeOneEighty
     *
     * @return void
     *
     * @dataProvider lessThanNegativeOneEighty
     */
    public function testRule_whenValueIsLessThanNegativeOneEighty_shouldReturnFalse(
        $lessThanNegativeOneEighty
    ) {
        $result = $this->rule->passes('attribute', $lessThanNegativeOneEighty);

        $this->assertFalse($result);
    }

    /**
     * Verifies that the rule fails when the longitude is greater than 180
     *
     * @param $greaterThanOneEighty
     *
     * @return void
     *
     * @dataProvider greaterThanOneEighty
     */
    public function testRule_whenValueIsGreaterThanOneEighty_shouldReturnFalse(
        $greaterThanOneEighty
    ) {
        $result = $this->rule->passes('attribute', $greaterThanOneEighty);

        $this->assertFalse($result);
    }

    /**
     * Verifies that the rule passes when the longitude is between -180 and 180
     *
     * @param $validLongitude
     *
     * @return void
     *
     * @dataProvider betweenNegativeOneEightyAndOneEighty
     */
    public function testRule_whenValueIsBetweenNegativeOneEightyAndOneEighty_shouldReturnTrue(
        $validLongitude
    ) {
        $result = $this->rule->passes('attribute', $validLongitude);

        $this->assertTrue($result);
    }

    /**
     * Returns an array of non-numeric longitudes
     *
     * @return array
     */
    public function nonNumericLongitudes(): array
    {
        return [
            [null],
            [true],
            [false],
            [[]],
            ['string'],
        ];
    }

    /**
     * Returns an array of numbers that are less than -180
     *
     * @return array
     */
    public function lessThanNegativeOneEighty(): array
    {
        return [
            [-182],
            [-181],
            [-180.00000001],
        ];
    }

    /**
     * Returns an array of numbers that are greater than 180
     *
     * @return array
     */
    public function greaterThanOneEighty(): array
    {
        return [
            [180.0000001],
            [181],
            [182],
        ];
    }

    /**
     * Returns an array of numbers that are between -180 and 180
     *
     * @return array
     */
    public function betweenNegativeOneEightyAndOneEighty(): array
    {
        $betweenNegativeOneEightyAndOneEighty = range(-180, 180);

        return array_map(
            function ($longitude) {
                return [$longitude];
            },
            $betweenNegativeOneEightyAndOneEighty
        );
    }
}
