<?php
namespace App;
use Illuminate\Database\Eloquent\Model;


class Place extends model
{
    protected $fillable = ['lat', 'lng', 'name'];


    public function getLocationByCoordinates(float $lat, float $lng, int $threshold)
    {
        $out = [];
        // should do haversine on db level, but is demo app so some iterations here
        foreach ($this->all()->toArray() as $k => $v) {
            $distance = $this->haversineGreatCircleDistance($lat, $lng, $v['lat'], $v['lng']);
            $v['distance'] = $distance;
            $out[] = $v;
        }
        usort($out, function ($a, $b) {
            if ($a['distance'] === $b['distance']) {
                return 0;
            }
            return floatval($a['distance']) < floatval($b['distance']) ? -1 : 1;
        });
        $out = array_map(function ($el) {
                unset($el['distance']);
                return $el;
            },
            $out
        );
        return array_slice($out, 0, $threshold);
    }

    /**
     * https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    private function haversineGreatCircleDistance(
        float $latitudeFrom,
        float $longitudeFrom,
        float $latitudeTo,
        float $longitudeTo,
        $earthRadius = 6371000):float
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}