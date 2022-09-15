<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\WithinRadiusRequest;
use Illuminate\Http\JsonResponse;

/**
 * Returns all locations within a coordinate's radius
 *
 * @package App\Http\Controllers\Locations
 *
 * @author  Peter Cortez <innov.petercortez@gmail.com>
 */
class WithinRadiusController extends Controller
{
    /**
     * @param \App\Http\Requests\Locations\WithinRadiusRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(WithinRadiusRequest $request): JsonResponse
    {
        return new JsonResponse(['data' => []]);
    }
}
