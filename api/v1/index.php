<?php 
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 36000");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$response = array();
$response["code"] = "data/not-found";
$response["message"] = "Sorry, no data has been discovered yet!!";

$url = $_SERVER["REQUEST_URI"];
$path = explode("?",$url)[0];
$path_array = explode("/",$path);
$endpoint = end($path_array);
if($endpoint == ""){
    array_pop($path_array);
    $endpoint = end($path_array);
}
$endpoint = $endpoint;
$query = explode("?",$url);
if(count($query) == 1){
    $query = "";
}else{
    $query = $query[1];
}

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$ip = getUserIpAddr();

$db = new mysqli("localhost","u400631424_virus","9874NESF","u400631424_coronavirus");

$sql_query = "INSERT INTO log(ip_address,endpoint,query) VALUES('$ip','$endpoint','$query')";
$db->query($sql_query);

// echo $_SERVER["HTTP_HOST"];

if($_SERVER["HTTP_HOST"] != "coronavirus.developerswork.online" && $_SERVER["HTTP_HOST"] != "localhost"){
    echo json_encode($response);
    die();
}

switch($endpoint){
    // https://coronavirus.developerswork.online/api/v1/global
    case "global":
        $query = "SELECT country_name as title,cases as total_cases,deaths as total_deaths,total_recovered,new_cases as total_new_cases_today,new_deaths as total_new_deaths_today FROM coronavirus_stats WHERE id IN(SELECT MAX(id) FROM coronavirus_stats GROUP BY country_name) and country_name LIKE '%GLOBAL%';";
        $data = [];
        $result = $db->query($query);
        if($result && $result->num_rows > 0)
            while($row = $result->fetch_assoc()){
                // print_r($row);
                array_push($data,$row);
            }
        $json["data"] = $data;
        $json["source"] = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        echo json_encode($json);
        break;
    // https://coronavirus.developerswork.online/api/v1/all_countries
    case "all_countries":
        $query = "SELECT country_name as title,cases as total_cases,deaths as total_deaths,total_recovered,new_cases as total_new_cases_today,new_deaths as total_new_deaths_today FROM coronavirus_stats WHERE id IN(SELECT MAX(id) FROM coronavirus_stats GROUP BY country_name) and country_name NOT LIKE '%GLOBAL%' ORDER BY country_name;";
        $data = [];
        $result = $db->query($query);
        if($result && $result->num_rows > 0)
            while($row = $result->fetch_assoc()){
                // print_r($row);
                array_push($data,$row);
            }
        $json["data"] = $data;
        $json["source"] = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        echo json_encode($json);
        break;
    // https://coronavirus.developerswork.online/api/v1/country?name=COUNTRY_NAME
    // COUNTRY_NAME's spaces should be replaced with %20
    case "country":
        $query = explode("=",$query);
        if(count($query) <= 1){
            echo json_encode($response);
            die();
        }else{
            $query = strtolower($query[1]);
            $query = str_replace("%20"," ",$query);
        }
        // echo $query;

        $query = "SELECT country_name as title,cases as total_cases,deaths as total_deaths,total_recovered,new_cases as total_new_cases_today,new_deaths as total_new_deaths_today,statistic_taken_at as recorded_on FROM coronavirus_stats WHERE country_name LIKE '%".$query."%' ORDER BY statistic_taken_at DESC;";
        // echo $query;
        $data = [];
        $result = $db->query($query);
        if($result && $result->num_rows > 0)
            while($row = $result->fetch_assoc()){
                // print_r($row);
                array_push($data,$row);
            }
        $json["data"] = $data;
        $json["source"] = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        echo json_encode($json);
        break;
    // https://coronavirus.developerswork.online/api/v1/list_countries
    case "list_countries":
        // EFFECTED COUNTRIES
        $query = "SELECT DISTINCT(UPPER(country_name)) AS name FROM `coronavirus_stats` ORDER BY country_name;";
        $data = [];
        $result = $db->query($query);
        if($result && $result->num_rows > 0)
            while($row = $result->fetch_assoc()){
                // print_r($row);
                array_push($data,$row["name"]);
            }
        $json["data"] = $data;
        $json["source"] = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        echo json_encode($json);
        break;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://coronavirus-monitor.p.rapidapi.com/coronavirus/affected.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: coronavirus-monitor.p.rapidapi.com",
                "x-rapidapi-key: 224f264f4bmsh332b4ca3626a7dep15f2b7jsn6fa2e2218a86"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // echo "cURL Error #:" . $err;
            $response = array();
            $response["code"] = "data/not-found";
            $response["message"] = "Sorry, no data has been discovered yet!!";
            echo json_encode($response);
        } else {
            $json = json_decode($response,true);
            $json = array("countries" => $json["affected_countries"]);
            echo json_encode($json);
        }
        break;
    default:
        echo json_encode($response);
    break;
}

