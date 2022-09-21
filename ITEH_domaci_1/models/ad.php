<?php
class Ad
{
    public function __construct($title, $brand, $model, $year, $price, $contact, $horsePower, $motor, $fuel, $additional, $imageName, $ownerId)
    {
        $this->title = $title;
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
        $this->price = $price;
        $this->contact = $contact;
        $this->horsePower = $horsePower;
        $this->motor = $motor;
        $this->fuel = $fuel;
        $this->additional = $additional;
        $this->ownerId = $ownerId;
        $this->imageName = $imageName;
    }


    public static function getAds($conn)
    {
        $sql = "SELECT A.id, A.title, A.brand, A.model, A.year, A.price, A.contact, A.horsePower, A.motor, A.fuel, A.additional, A.ownerId, A.image, A.date_created, U.username 
        FROM ADVERTISEMENT A JOIN USER U ON U.id = A.ownerId";

        $result = $conn->query($sql);
        $array = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $array[] = $row;
            }
        }
        return $array;
    }

    public function insert($conn)
    {
        $sql = "INSERT INTO advertisement 
        VALUES (NULL, '$this->title', '$this->brand', '$this->model', '$this->year',
        '$this->price','$this->contact','$this->horsePower','$this->motor','$this->fuel',
        '$this->additional','$this->ownerId', NOW(), '$this->imageName')";

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return $conn->error;
        }
    }

    public static function deleteAd($conn, $adId)
    {
        $sql = "DELETE FROM advertisement WHERE id='$adId'";

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return $conn->error;
        }
    }

    public function update($conn, $id)
    {
        $sql = "UPDATE advertisement SET";

        $sql .= " title='$this->title'";
        $sql .= ", brand='$this->brand'";
        $sql .= ", model='$this->model'";
        $sql .= ", year='$this->year'";
        $sql .= ", price='$this->price'";
        $sql .= ", contact='$this->contact'";
        $sql .= ", horsePower='$this->horsePower'";
        $sql .= ", motor='$this->motor'";
        $sql .= ", fuel='$this->fuel'";
        $sql .= ", additional='$this->additional'";

        if (!empty($this->imageName)) {
            $sql .= ", image='$this->imageName'";
        }
        $sql .= " WHERE id='$id';";

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return $conn->error;
        }
    }

    public static function filterAds($conn, $brand, $priceFrom, $priceTo, $yearFrom, $yearTo)
    {
        $sql = "SELECT A.id, A.title, A.brand, A.model, A.year, A.price, A.contact, A.horsePower, A.motor, A.fuel, A.additional, A.ownerId, A.date_created, A.image, U.username 
        FROM ADVERTISEMENT A JOIN USER U ON U.id = A.ownerId WHERE 1=1";


        if (!empty($brand)) {
            $brand = strtolower($brand);
            $sql .= " AND LOWER(A.brand) LIKE '%$brand%'";
        }
        if (!empty($priceFrom)) {
            $priceFrom = (int)$priceFrom;
            $sql .= " AND A.price >= $priceFrom";
        }
        if (!empty($priceTo)) {
            $priceTo = (int)$priceTo;
            $sql .= " AND A.price <= $priceTo";
        }
        if (!empty($yearFrom)) {
            $yearFrom = (int)$yearFrom;
            $sql .= " AND A.year >= $yearFrom";
        }
        if (!empty($yearTo)) {
            $yearTo = (int)$yearTo;
            $sql .= " AND A.year <= $yearTo";
        }

        $result = $conn->query($sql);
        $array = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $array[] = $row;
            }
            return $array;
        }
    }
}
