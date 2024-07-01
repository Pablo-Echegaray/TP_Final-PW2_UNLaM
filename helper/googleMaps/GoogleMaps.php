<?php

class GoogleMaps	{
    private $tableName = "google_maps_persistence";
    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getMarkers(){
        return $this->database->query(
            "SELECT * 
             FROM google_maps_persistence"
        );
    }
    public function getMarkByUser($userId){
        return $this->database->query(
            "SELECT * 
             FROM google_maps_persistence
             WHERE id_user = $userId"
        );
    }

    public function getMarkersBlankLatLng(){
        return $this->database->query(
            "SELECT * 
             FROM google_maps_persistence 
             WHERE lat IS NULL 
             AND lng IS NULL"
        );
    }

    public function saveCoordinates($userId, $city, $country, $lat, $lng){
        $this->database->execute(
            "INSERT INTO google_maps_persistence (id_user, city, country, lat, lng)
                VALUES ($userId, '$city', '$country', '$lat', $lng);"
        );
    }
}

?>