<?php

namespace Tests\Feature\app\Http\Controllers\Locations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;
use function range;
use function route;

class WithinRadiusControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifies that a 422 error is returned when the `rad` query string parameter
     * is missing.
     *
     * @return void
     */
    public function testWhenRad_isMissing_shouldReturn422Error()
    {
        $request = $this->getJson($this->route());

        $request->assertJsonValidationErrors([
            'rad' => ['The rad field is required.'],
        ]);
        $request->assertUnprocessable();
    }

    /**
     * Verifies that a 422 error is returned when the `lat` query string parameter
     * is missing.
     *
     * @return void
     */
    public function testWhenLat_isMissing_shouldReturn422Error()
    {
        $request = $this->getJson($this->route());

        $request->assertJsonValidationErrors([
            'lat' => ['The lat field is required.'],
        ]);
        $request->assertUnprocessable();
    }

    /**
     * Verifies that a 422 error is returned when the `long` query string parameter
     * is missing.
     *
     * @return void
     */
    public function testWhenLong_isMissing_shouldReturn422Error()
    {
        $request = $this->getJson($this->route());

        $request->assertJsonValidationErrors([
            'long' => ['The long field is required.'],
        ]);
        $request->assertUnprocessable();
    }

    /**
     * Verifies that when an invalid radius is given, a 422 error will be returned
     *
     * @param $invalidRadius
     * @param $expectedErrorMessage
     *
     * @return void
     *
     * @dataProvider invalidRadius
     */
    public function testWhenRad_isInvalid_shouldReturn422Error(
        $invalidRadius,
        $expectedErrorMessage
    ) {
        $request = $this->getJson($this->route(['rad' => $invalidRadius]));

        $request->assertJsonValidationErrors([
            'rad' => [$expectedErrorMessage],
        ]);
        $request->assertUnprocessable();
    }

    /**
     * Verifies that when an invalid latitude is given, a 422 error will be returned
     *
     * @param $invalidLatitude
     * @param $expectedErrorMessage
     *
     * @return void
     *
     * @dataProvider invalidLatitudes
     */
    public function testWhenLat_isInvalid_shouldReturn422Error(
        $invalidLatitude,
        $expectedErrorMessage
    ) {
        $request = $this->getJson($this->route(['lat' => $invalidLatitude]));

        $request->assertJsonValidationErrors([
            'lat' => [$expectedErrorMessage],
        ]);
        $request->assertUnprocessable();
    }

    /**
     * Verifies that when an invalid longitude is given, a 422 error will be returned
     *
     * @param $invalidLongitude
     * @param $expectedErrorMessage
     *
     * @return void
     *
     * @dataProvider invalidLongitudes
     */
    public function testWhenLong_isInvalid_shouldReturn422Error(
        $invalidLongitude,
        $expectedErrorMessage
    ) {
        $request = $this->getJson($this->route(['long' => $invalidLongitude]));

        $request->assertJsonValidationErrors([
            'long' => [$expectedErrorMessage],
        ]);
        $request->assertUnprocessable();
    }

    /**
     * Given a valid latitude, longitude, and radius, the expected location names
     * will be returned assuming that the data in the database is equivalent to our
     * seeder {@see \Database\Seeders\LocationSeeder::$data data}.
     *
     * @param float $latitude
     * @param float $longitude
     * @param float $radius
     * @param array $expectedLocations
     *
     * @return void
     *
     * @dataProvider validParamsAndExpectedResults
     */
    public function testWhenData_isValid_shouldCalculateCorrectly(
        float $latitude,
        float $longitude,
        float $radius,
        array $expectedLocations
    ) {
        $this->seed();
        $route = $this->route([
            'lat' => $latitude,
            'long' => $longitude,
            'rad' => $radius,
        ]);

        $request = $this->getJson($route);

        $this->assertEquals(
            $expectedLocations,
            Arr::pluck($request->json('data'), 'name')
        );
    }

    /**
     * Verifies that the locations are returned ordered by their distance from the
     * source, in ascending order.
     *
     * @return void
     */
    public function testWhenData_isValid_shouldOrderByDistanceInAscendingOrder()
    {
        $this->seed();
        $route = $this->route([
            'lat' => 51.58360411,
            'long' => -2.30271250,
            'rad' => 10,
        ]);

        $request = $this->getJson($route);

        $this->assertEquals(
            [
                '106 The Barley Lea',
                'George Street',
                '95 Ingelow Road',
                'Wone International Ltd',
                'Greenstone Pub & Restaurant',
                '1 Clifton Avenue',
                'Best Western Rockingham Forest Hotel',
                'Downhills Park Road',
                '31 Hatfield Road',
                '8 Highclere Road',
            ],
            Arr::pluck($request->json('data'), 'name')
        );
    }

    /**
     * Returns an array of invalid radii with an expected error messages for when
     * they occur.
     *
     * @return \string[][]
     */
    public function invalidRadius(): array
    {
        return [
            ['invalid-radius', 'The rad must be a number.'],
            ['[]', 'The rad must be a number.'],
            ['1a', 'The rad must be a number.'],
        ];
    }

    /**
     * Returns an array of invalid latitudes with an expected error messages for when
     * they occur.
     *
     * @return \string[][]
     */
    public function invalidLatitudes(): array
    {
        return [
            ['invalid-latitude', 'The lat must be a number between -90 and 90.'],
            ['[]', 'The lat must be a number between -90 and 90.'],
            ['1a', 'The lat must be a number between -90 and 90.'],
        ];
    }

    /**
     * Returns an array of invalid longitudes with an expected error messages for
     * when they occur.
     *
     * @return \string[][]
     */
    public function invalidLongitudes(): array
    {
        return [
            ['invalid-longitude', 'The long must be a number between -180 and 180.'],
            ['[]', 'The long must be a number between -180 and 180.'],
            ['1a', 'The long must be a number between -180 and 180.'],
        ];
    }

    /**
     * Returns an array of coordinates and radii, and an expected list of location
     * names that should be returned by the controller.
     *
     * @link {https://www.distance.to/ To test out the calculations}
     *
     * @return array
     */
    public function validParamsAndExpectedResults(): array
    {
        return [
            [51.47560393, -2.38071671, 1, ['Toyota Taunton']],
            [
                51.58360411, -2.30271250, 5, [
                    '106 The Barley Lea',
                    'George Street',
                    '95 Ingelow Road',
                ]
            ],
        ];
    }

    /**
     * Returns the route that we're working on, with query string parameters.
     *
     * @param array $params
     *
     * @return string
     */
    protected function route(array $params = []): string
    {
        return route('locations.within-radius', $params);
    }
}
