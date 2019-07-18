<?php

namespace App\Http\Controllers;

use App\Place;
use Illuminate\Http\Request;

class CoordinateController extends Controller
{
    public function __construct()
    {
        //
    }

    public function createCoordinates()
    {
        return 'ABC';
    }

    public function getCoordinates(Request $request)
    {
        $params = json_decode($request->getContent(), 1);
        $params['threshold'] = $params['threshold'] ?? 1;
        $place = new Place();
        return $place->getLocationByCoordinates(
            $params['lat'],
            $params['lng'],
            $params['threshold']
        );
    }
}
