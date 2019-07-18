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

    /*
     * @return array [id_of_created_place => name_of_created_place]
     */
    public function createCoordinates(Request $request)
    {
        $params = json_decode($request->getContent(), 1);
        $out = [];
        foreach ($params as $param) {
            $place = new Place($param);
            $place->save();
            $out[$place->id] = $place->name;
        }
        return $out;
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
