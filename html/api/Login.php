<?php
    $inData = getRequestInfo();
    
    $UserID = 0;
    $FirstName = "";
    $LastName = "";
    $Email = "";
    $Role = "";

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
    if( $conn->connect_error )
    {
        returnWithError( $conn->connect_error );
    }
    else
    {
        $stmt = $conn->prepare("SELECT UserID, FirstName, LastName, Email, Role FROM Users WHERE Login=? AND Password =?");
        $stmt->bind_param("ss", $inData["login"], $inData["password"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if( $row = $result->fetch_assoc() )
        {
            returnWithInfo($row['UserID'], $row['FirstName'], $row['LastName'], $row['Email'], $row['Role']);
        }
        else
        {
            returnWithError("No Records Found");
        }

        $stmt->close();
        $conn->close();
    }
    
    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson( $obj )
    {
        header('Content-type: application/json');
        echo $obj;
    }
    
    function returnWithError( $err )
    {
        $retValue = '{"UserID":0,"FirstName":"","LastName":"","Email":"","Role":"","error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
    
    function returnWithInfo( $UserID, $FirstName, $LastName, $Email, $Role )
    {
        $retValue = '{"UserID":' . $UserID . ',"FirstName":"' . $FirstName . '","LastName":"' . $LastName . '","Email":"' . $Email . '","Role":"' . $Role . '","error":""}';
        sendResultInfoAsJson( $retValue );
    }
?>
