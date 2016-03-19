<?php
header('Content-Type: application/json');

$link = mysqli_connect("mysqlstudent", "nickdhondteiroh3", "Zosh0ohbee0R", "nickdhondteiroh3");

$response = array();

$response["success"] = false;

if (!$link) {
    $response["error"][] = ["Error fetching trees", mysqli_connect_errno(), mysqli_connect_error()];
    exit;
}

if (!empty($_GET["age"]) && !empty($_GET["distance"]) && !empty($_GET["lat"]) && !empty($_GET["lon"])) {
    $age = intval($_GET["age"]);
    $distance = intval($_GET["distance"]);
    $lat = floatval($_GET["lat"]);
    $lon = floatval($_GET["lon"]);

    if (is_int($age) && is_int($distance) && is_float($lat) && is_float($lon)) {
        $bomen = array();
        $candidates = array();

        $beginage = 2016 - $age + 10;
        $endage = 2016 - $age - 10;

        $query = "SELECT * FROM bomen WHERE Plantjaar < " . $beginage . " AND Plantjaar > " . $endage;

        if ($result = mysqli_query($link, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (haversineGreatCircleDistance($lat, $lon, $row["Lat"], $row["Lon"]) <= $distance) $bomen[] = $row;
            }

            mysqli_free_result($result);
        }

        $total_candidates = count($bomen);
        $candidate_indexes = array();

        $max_count = 5;

        if ($total_candidates < $max_count) $max_count = $total_candidates;

        while(count($candidate_indexes) < $max_count) {
            do {
                $rand = rand(0, $total_candidates - 1);
            } while (in_array($rand, $candidate_indexes));

            $candidate_indexes[] = $rand;

            //print_r($rand);
        }

        foreach($candidate_indexes as $index) {
            $candidates[] = $bomen[$index];
        }

        //print_r($candidates);

        $response["success"] = true;
        $response["count"] = count($candidates);
        $response["bomen"] = $candidates;
    } else {
        $response["error"][] = "Parameters must be integers or floats";
    }
} else {
    $response["error"][] = "GET parameters age, distance(meter), lat and lon expected";
}

echo json_encode($response);

mysqli_close($link);

function haversineGreatCircleDistance(
    $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
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

?>