<?php

    $inData = getRequestInfo();

    $login = $inData["login"];
    $password = $inData["password"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331", "COP4331"); 
    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        // Check if the login already exists
        $stmt = $conn->prepare("SELECT * FROM Users WHERE Login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            returnWithError("This login is already taken");
            $stmt->close();
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO Users (Login, Password, firstName, lastName) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $login, $password, $firstName, $lastName);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                returnWithInfo($firstName, $lastName, $stmt->insert_UserID);
            } else {
                returnWithError("Error in registering user");
            }

            $stmt->close();
        }

        $conn->close();
    }

    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err) {
        $retValue = '{"UserID":0, "firstName":"", "lastName":"", "error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }

    function returnWithInfo($firstName, $lastName, $UserID) {
        $retValue = '{"UserID":' . $UserID . ', "firstName":"' . $firstName . '", "lastName":"' . $lastName . '", "error":""}';
        sendResultInfoAsJson($retValue);
    }
?>
