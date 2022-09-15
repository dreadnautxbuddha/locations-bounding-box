<?php

namespace Tests\Feature\app\Http\Controllers\Locations;

use Tests\TestCase;
use function range;
use function route;

class WithinRadiusControllerTest extends TestCase
{
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
