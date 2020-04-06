<?php
function loadRegistrations($filename){
    $jsonData = file_get_contents($filename);
    return json_decode($jsonData, true);
}

function saveDataJSON($filename, $name, $email, $phone)
{
    try {
        $contact = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        );
        // converts json data into array
        $arr_data = loadRegistrations($filename);
        // Push user data to array
        array_push($arr_data, $contact);
        //Convert updated array to JSON
        $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);
        //write json data into data.json file
        file_put_contents($filename, $jsondata);
        echo "Saved!";
    } catch (Exception $e) {
        echo 'Error!: ', $e->getMessage(), "\n";
    }
}


$nameErr = NULL;
$emailErr = NULL;
$phoneErr = NULL;
$name = NULL;
$email = NULL;
$phone = NULL;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $has_error = false;

    if (empty($name)) {
        $nameErr = "Username must not be empty!";
        $has_error = true;
    }

    if (empty($email)) {
        $emailErr = "Email must not be empty!";
        $has_error = true;
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Wrong email format (xxx@xxx.xxx.xxx)!";
            $has_error = true;
        }
    }

    if (empty($phone)) {
        $phoneErr = "Phone must not be empty!";
        $has_error = true;
    }

    if ($has_error === false) {
        saveDataJSON("data.json", $name, $email, $phone);
        $name = NULL;
        $email = NULL;
        $phone = NULL;
    }
}

