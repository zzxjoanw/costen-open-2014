<?php
/**
 * Created by PhpStorm.
 * User: Laura 5
 * Date: 4/10/2016
 * Time: 6:01 AM
 */

function getUserInfo($connection,$fName="",$lName="")
{
    if($fName=="")
    {
        $sql = "SELECT AGANumber,fName,lName,dofb,country,street,city,state,zip,email,phone,club,regTime FROM tblContestant";
        $preparedStatement = $connection->prepare($sql);
        $preparedStatement->execute();
    }
    else
    {
        $sql = "SELECT AGANumber,fName,lName,dofb,country,street,city,state,zip,email,phone,club,regTime
                                 FROM tblContestant
                                 WHERE fName = ? AND lName = ?";
        $preparedStatement = $connection->prepare($sql);
        $preparedStatement->bind_param("ss",$fName,$lName);
    }

    $preparedStatement->execute();
    $preparedStatement->bind_result($AGANumber,$fName,$lName,$dofb,$country,$street,$city,$state,$zip,$email,$phone,$club,$regTime);
    $list = array();
    while($preparedStatement->fetch())
    {
        $row = array();
        array_push($row,$AGANumber);
        array_push($row,$fName);
        array_push($row,$lName);
        array_push($row,$dofb);
        array_push($row,$country);
        array_push($row,$street);
        array_push($row,$city);
        array_push($row,$state);
        array_push($row,$zip);
        array_push($row,$email);
        array_push($row,$phone);
        array_push($row,$club);
        array_push($row,$regTime);
        array_push($list,$row);
    }
    
    return $list;
}

function updateUser($connection, $_POST)
{
    if(!$pps = $connection->prepare("UPDATE tblContestant SET fName=?,lName=?, dofb=?, country=?, street=?,
                        city=?,state=?, zip=?, email=?, phone=?, club=? WHERE AGANumber = ?"))
    {
        echo "Error: " . $pps->error;
    }

    if(!$pps->bind_param("ssssssssssss", $_POST['fname'], $_POST['lname'], $_POST['dofb'],
        $_POST['country'], $_POST['street'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['email'],
        $_POST['phone'], $_POST['club'], $_GET['num']))
    {
        echo "Error: " . $pps->error;
    }

    if(!$pps->execute())
    {
        echo "Error: " . $pps->error;
    }

    if(!$pps->close())
    {
        echo "Error: " . $pps->error;
    }
}

function deleteUser($connection,$num,$fName,$lName)
{
    $preparedStatement = $connection->prepare("DELETE FROM tblContestant WHERE AGANumber = ? AND fName = ? AND lName = ?");
    $preparedStatement->bind_param("sss",$num,$fName,$lName);
    $preparedStatement->execute();
    $preparedStatement->close();
}

function getTournamentInfo($connection)
{
    $sql = "SELECT * FROM tournamentInfo";
    $preparedStatement = $connection->prepare($sql);
    $preparedStatement->execute();
    $preparedStatement->bind_result($startDate,$endDate,$preregStart,$preregEnd);
    
    $list = array();
    while($preparedStatement->fetch())
    {
        array_push($list,$startDate);
        array_push($list,$endDate);
        array_push($list,$preregStart);
        array_push($list,$preregEnd);
    }
    
    return $list;
}