<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 36000");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$db = new mysqli("localhost","u400631424_virus","9874NESF","u400631424_coronavirus");

    // GLOBAL STATS
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://coronavirus-monitor.p.rapidapi.com/coronavirus/worldstat.php",
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
    } else {
        $json = json_decode($response,true);
        
        $columns = "";
        $values = "";
        foreach($json as $key => $value){
            if($key == "total_cases")
                $key = "cases";
            else if($key == "total_deaths")
                $key = "deaths";
            $columns = $columns.$key. ",";
            $values = $values . "'" . $value . "',";
        }
        $columns = $columns."country_name";
        $values = $values . "'GLOBAL'";

        // $columns = rtrim($columns,",");
        // $values = rtrim($values,",");

        $query = "INSERT INTO coronavirus_stats($columns) VALUES($values);";
        // echo $query;
        $result = $db->query($query);

        // echo json_encode($json);
    }

    // COUNTRY BASED STATS
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://coronavirus-monitor.p.rapidapi.com/coronavirus/cases_by_country.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type:application/json",
            "x-rapidapi-host: coronavirus-monitor.p.rapidapi.com",
            "x-rapidapi-key: 224f264f4bmsh332b4ca3626a7dep15f2b7jsn6fa2e2218a86"
        ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        // echo "cURL Error #:" . $err;
    } else {
        // echo json_encode(json_decode($response));
        $json = json_decode($response,true);
        
        $valueList = "";
        foreach($json["countries_stat"] as $country){
            $columns = "";
            $values = "";
            // print_r($country);
            foreach($country as $key => $value){
                if($key == "region")
                    continue;
                if($key == "country_name")
                    $value = strtolower($value);
                $columns = $columns.$key. ",";
                $values = $values . "'" . $value . "',";
            }
            $columns = $columns."statistic_taken_at";
            $values = $values . "'".$json['statistic_taken_at']."'";
            // $valueList = $valueList . "($values),";
            $query = "INSERT INTO coronavirus_stats($columns) VALUES($values);";
            // echo $query;
            $result = $db->query($query);
        }
        

        // $columns = rtrim($columns,",");
        // $values = rtrim($valueList,",");

        // $query = "INSERT INTO coronavirus_stats($columns) VALUES $values;";
        // echo $query;
        // $result = $db->query($query);

        // echo json_encode($json);
    }
echo "CRON JOB COMPLETED!!!";