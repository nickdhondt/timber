<?php
header('Content-Type: application/json');

$link = mysqli_connect("mysqlstudent", "nickdhondteiroh3", "Zosh0ohbee0R", "nickdhondteiroh3");

$response = array();

$response["success"] = false;

if (!$link) {
    $response["error"][] = [mysqli_connect_errno(), mysqli_connect_error()];
    exit;
}

if (!empty($_GET["age"]) && !empty($_GET["lat"]) && !empty($_GET["lon"])) {
    $age = intval($_GET["age"]);
    $lat = intval($_GET["lat"]);
    $lon = intval($_GET["lon"]);

    if (is_int($age) && is_int(lat) && is_int()) {
        $bomen = array();

        $beginage = 2016 - $age + 10;
        $endage = 2016 - $age - 10;

        $query = "SELECT * FROM bomen WHERE Plantjaar < " . $beginage . " AND Plantjaar > " . $endage;

        if ($result = mysqli_query($link, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {

                $bomen[] = $row;
            }

            mysqli_free_result($result);
        }

        if (count($bomen) > 0) {
            $response["success"] = true;

            $response["bomen"] = $bomen;

            foreach($bomen as $key => $boom) {

            }
        } else {
            $response["error"][] = "Error fetching trees";
        }
    } else {
        $response["error"][] = "Parameters must be integers";
    }
} else {
    $response["error"][] = "GET parameters age, lat and lon expected";
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