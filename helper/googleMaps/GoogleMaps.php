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
        echo "Guardando coordenadas";
        $this->database->execute(
            "INSERT INTO google_maps_persistence (id_user, city, country, lat, lng)
                VALUES ($userId, '$city', '$country', '$lat', $lng);"
        );
    }

    /*
        public function getCollegesBlankLatLng() {
            $sql = "SELECT * FROM $this->tableName WHERE lat IS NULL AND lng IS NULL";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAllColleges() {
            $sql = "SELECT * FROM $this->tableName";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function updateCollegesWithLatLng() {
            $sql = "UPDATE $this->tableName SET lat = :lat, lng = :lng WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':lat', $this->lat);
            $stmt->bindParam(':lng', $this->lng);
            $stmt->bindParam(':id', $this->id);

            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        */
}

?>