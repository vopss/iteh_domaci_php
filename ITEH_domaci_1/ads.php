<?php
require_once('db_connection.php');
require_once('models/ad.php');

if (isset($_GET['getAll'])) {
    echo json_encode(Ad::getAds($conn));
}



if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = file_get_contents("php://input");
    $adId = json_decode($data);
    $adId = $adId->id;
    $result = Ad::deleteAd($conn, $adId);
    if ($result) {
        echo "Success";
    } else {
        echo "Greška: " . $result;
    };
}

if (isset($_POST['title']) && isset($_POST['id'])) {
    $id = sanitizeInputData($_POST['id']);
    $title = sanitizeInputData($_POST['title']);
    $brand = sanitizeInputData($_POST['brand']);
    $model = sanitizeInputData($_POST['model']);
    $year = sanitizeInputData($_POST['year']);
    $price = sanitizeInputData($_POST['price']);
    $contact = sanitizeInputData($_POST['contact']);
    $horsePower = sanitizeInputData($_POST['horsePower']);
    $motor = sanitizeInputData($_POST['motor']);
    $fuel = sanitizeInputData($_POST['fuel']);
    $additional = sanitizeInputData($_POST['additional']);

    $imageName = null;
    if (isset($$_FILES['image2'])) {
        $image = $_FILES['image2'];

        $image_extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $image['name'] = guidv4() . '.' . $image_extension;
        move_uploaded_file($image['tmp_name'], './resources/images/ad_images/' . $image['name']);
        $imageName = $image['name'];
    }
    $ad = new Ad($title, $brand, $model, $year, $price, $contact, $horsePower, $motor, $fuel, $additional, $imageName, null);
    $result = $ad->update($conn, $id);

    if ($result) {
        header("Location: index.php?message='Oglas je uspešno promenjen'");
    } else {
        header("Location: index.php?message='Greška prilikom izmene oglasa'");
    }
}

if (isset($_POST['title']) && !isset($_POST['id'])) {
    session_start();

    $title = sanitizeInputData($_POST['title']);
    $brand = sanitizeInputData($_POST['brand']);
    $model = sanitizeInputData($_POST['model']);
    $year = sanitizeInputData($_POST['year']);
    $price = sanitizeInputData($_POST['price']);
    $contact = sanitizeInputData($_POST['contact']);
    $horsePower = sanitizeInputData($_POST['horsePower']);
    $motor = sanitizeInputData($_POST['motor']);
    $fuel = sanitizeInputData($_POST['fuel']);
    $additional = sanitizeInputData($_POST['additional']);
    $ownerId = sanitizeInputData($_SESSION['user_id']);
    $image = $_FILES['image'];

    $image_extension = pathinfo($image['name'], PATHINFO_EXTENSION);
    $image['name'] = guidv4() . '.' . $image_extension;
    move_uploaded_file($image['tmp_name'], './resources/images/ad_images/' . $image['name']);

    $imageName = $image['name'];
    if (
        isset($title) && isset($brand) && isset($model) && isset($year) && isset($price) && isset($contact) && isset($horsePower) &&
        isset($motor) && isset($fuel) && isset($additional) && isset($ownerId) && isset($image) && isset($imageName)
    ) {
        $ad = new Ad($title, $brand, $model, $year, $price, $contact, $horsePower, $motor, $fuel, $additional, $imageName, $ownerId);
        if ($ad->insert($conn)) {
            header("location: index.php?message='Oglas za vozilo je uspešno postavljen'");
            exit();
        } else {
            header("location: index.php?message='Error adding new advertisement'");
            exit();
        };
    } else {
        header("location: index.php?message='Invalid request format'");
    }
}

if (isset($_GET['filter'])) {
    echo json_encode(Ad::filterAds(
        $conn,
        sanitizeInputData($_POST['brand']),
        sanitizeInputData($_POST['priceFrom']),
        sanitizeInputData($_POST['priceTo']),
        sanitizeInputData($_POST['yearFrom']),
        sanitizeInputData($_POST['yearTo'])
    ));
}

function sanitizeInputData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function guidv4($data = null)
{
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
