<?php

namespace Tests\Unit\app\Rules\Geolocation;

use App\Rules\Geolocation\Latitude;
use Tests\TestCase;
use function array_map;
use function range;

class LatitudeTest extends TestCase
{
    /**
     * @var \App\Rules\Geolocation\Latitude
     */
    protected $rule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule = app(Latitude::class);
    }

    /**
     * Verifies that the rule returns false when non-numeric values are passed to it
     *
     * @param $invalidValue
     *
     * @return void
     *
     * @dataProvider nonNumericLatitudes
     */
    public function testRule_whenValueIsNonNumeric_shouldReturnFalse($invalidValue)
    {
        $result = $this->rule->passes('attribute', $invalidValue);

        $this->assertFalse($result);
    }

    /**
     * Verifies that the rule fails when the latitude is less than -90
     *
     * @param $lessThanNegativeNinety
     *
     * @return void
     *
     * @dataProvider lessThanNegativeNinety
     */
    public function testRule_whenValueIsLessThanNegativeNinety_shouldReturnFalse(
        $lessThanNegativeNinety
    ) {
        $result = $this->rule->passes('attribute', $lessThanNegativeNinety);

        $this->assertFalse($result);
    }

    /**
     * Verifies that the rule fails when the latitude is greater than 90
     *
     * @param $greaterThanNinety
     *
     * @return void
     *
     * @dataProvider greaterThanNinety
     */
    public function testRule_whenValueIsGreaterThanNinety_shouldReturnFalse(
        $greaterThanNinety
    ) {
        $result = $this->rule->passes('attribute', $greaterThanNinety);

        $this->assertFalse($result);
    }

    /**
     * Verifies that the rule passes when the latitude is between -90 and 90
     *
     * @param $validLatitude
     *
     * @return void
     *
     * @dataProvider betweenNegativeNinetyAndNinety
     */
    public function testRule_whenValueIsBetweenNegativeNinetyAndNinety_shouldReturnTrue($validLatitude)
    {
        $result = $this->rule->passes('attribute', $validLatitude);

        $this->assertTrue($result);
    }

    /**
     * Returns an array of non-numeric latitudes
     *
     * @return array
     */
    public function nonNumericLatitudes(): array
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
     * Returns an array of numbers that are less than -90
     *
     * @return array
     */
    public function lessThanNegativeNinety(): array
    {
        return [
            [-92],
            [-91],
            [-90.00000001],
        ];
    }

    /**
     * Returns an array of numbers that are greater than 90
     *
     * @return array
     */
    public function greaterThanNinety(): array
    {
        return [
            [90.0000001],
            [91],
            [92],
        ];
    }

    /**
     * Returns an array of numbers that are between -90 and 90
     *
     * @return array
     */
    public function betweenNegativeNinetyAndNinety(): array
    {
        $betweenNegativeNinetyAndNinety = range(-90, 90);

        return array_map(
            function ($latitude) {
                return [$latitude];
            },
            $betweenNegativeNinetyAndNinety
        );
    }
}
