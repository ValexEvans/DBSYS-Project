<?php
    $inData = getRequestInfo();
    
    $ID = 0;
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
        $stmt = $conn->prepare("SELECT ID, FirstName, LastName, Email, Role FROM Users WHERE Login=? AND Password =?");
        $stmt->bind_param("ss", $inData["login"], $inData["password"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if( $row = $result->fetch_assoc() )
        {
            returnWithInfo($row['ID'], $row['FirstName'], $row['LastName'], $row['Email'], $row['Role']);
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
        $retValue = '{"ID":0,"FirstName":"","LastName":"","Email":"","Role":"","error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }
    
    function returnWithInfo( $ID, $FirstName, $LastName, $Email, $Role )
    {
        $retValue = '{"ID":' . $ID . ',"FirstName":"' . $FirstName . '","LastName":"' . $LastName . '","Email":"' . $Email . '","Role":"' . $Role . '","error":""}';
        sendResultInfoAsJson( $retValue );
    }
?>
