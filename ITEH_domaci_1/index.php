<?php
require_once('models/ad.php');
require_once('db_connection.php');

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert($message)</script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/index.css">
    <title>Automobili.com</title>
</head>

<body>
    <div>
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark side-menu" style="width: 240px;">
            <div>
                <a class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <div class="username-div">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                        </svg>
                        <div id="username"><?php echo $_SESSION['username']; ?></div>
                    </div>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <div class="newAd-div">
                            <button type="button" class="btn btn-success" onclick="addNewAd()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-plus-fill" viewBox="0 0 16 16">
                                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM8.5 7v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 1 0z" />
                                </svg>
                                Novi oglas
                            </button>
                        </div>
                    </li>
                    <li>
                        <div class="edit-profile-div">
                            <button type="button" class="btn btn-success" onclick="editUserData()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"></path>
                                </svg>
                                Izmeni profil
                            </button>
                        </div>
                    </li>
                    <li>
                        <div class="delete-profile-div">
                            <button type="button" class="btn btn-danger" onclick="popDeleteProfileModal(true)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                                </svg>
                                Obriši profil
                            </button>
                        </div>
                    </li>
                    <li>
                        <div class="logout-div">
                            <button type="button" class="btn btn-danger" onclick="logoutUser()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                </svg>
                                Odjavi se
                            </button>
                        </div>
                    </li>
                    <hr>
                    <li>
                        <div>
                            <label for="">Sortiraj po:</label>
                            <select id="sort-select" class="form-select form-select-sm" aria-label=".form-select-sm example" onchange="sortAds()">
                                <option value="price" selected>Cena</option>
                                <option value="year">Godina</option>
                                <option value="horsePower">Broj konjskih snaga</option>
                                <option value="date_created">Datum oglasa</option>
                            </select>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sortOrderRadio" id="sortOrderRadio1" value="asc" onchange="sortAds()">
                                <label class="form-check-label" for="sortOrderRadio1">
                                    Rastuće
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sortOrderRadio" id="sortOrderRadio2" value="desc" checked onchange="sortAds()">
                                <label class="form-check-label" for="sortOrderRadio2">
                                    Opadajuće
                                </label>
                            </div>
                        </div>
                    </li>
                </ul>
                <hr>
            </div>
        </div>
        <main>
            <header class="filter-div p-3 bg-dark text-white">
                <div class="container">
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

                        <div class="first-row">
                            <div>
                                <label for="">Marka</label>
                                <input id="filter-brand" type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
                            </div>
                            <div class="price-div">
                                <div>
                                    <label for="">Cena od</label>
                                    <input id="filter-priceFrom" type="number" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
                                </div>
                                <div>
                                    <label for="">Cena do</label>
                                    <input id="filter-priceTo" type="number" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
                                </div>
                            </div>
                            <div class="year-div">
                                <div>
                                    <label for="">Godina od</label>
                                    <input id="filter-yearFrom" type="number" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
                                </div>
                                <div>
                                    <label for="">Godina do</label>
                                    <input id="filter-yearTo" type="number" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
                                </div>
                            </div>
                            <button type="button" class="search-icon btn btn-success" onclick="filterAds()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            <div class="ads-container">


            </div>
        </main>
    </div>

    <div id="addNewAdModal">
        <div class="addNewAdModalTitle">Popunite polja ispod kako biste kreirali oglas za prodaju vozila</div>
        <form method="POST" action="ads.php" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Naslov oglasa" required />
            <input type="text" name="brand" placeholder="Marka vozila" required />
            <input type="text" name="model" placeholder="Model vozila" required />
            <input type="number" name="year" placeholder="Godina proizvodnje" required />
            <input type="number" name="price" placeholder="Cena vozila" required />
            <input type="text" name="contact" placeholder="Kontakt (npr. broj telefona, email)" required />
            <input type="number" name="horsePower" placeholder="Broj konjskih snaga" required />
            <input type="text" name="motor" placeholder="Motor" required />
            <input type="text" name="fuel" placeholder="Gorivo" required />
            <textarea name="additional" cols="30" rows="10" placeholder="Dodatne informacije"></textarea>
            <div class="files-div">
                <label for="files" class="btn">Dodaj sliku</label>
                <input type="file" id="files" name="image" accept=".img, .jpeg, .jpg, .png, .jfif" value="Dodaj sliku" required />
            </div>
            <button type="submit" class="btn btn-primary">Sačuvaj</button>
        </form>
        <button type="button" class="btn btn-danger close-modal-btn" onclick="closeAddNewAdModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"></path>
            </svg>
        </button>
    </div>

    <div id="editAdModal">
        <div class="addNewAdModalTitle">Popunite polja ispod kako biste kreirali oglas za prodaju vozila</div>
        <form method="POST" action="ads.php" enctype="multipart/form-data">
            <input type="text" name="id" hidden placeholder="id" required />
            <input type="text" name="title" placeholder="Naslov oglasa" required />
            <input type="text" name="brand" placeholder="Marka vozila" required />
            <input type="text" name="model" placeholder="Model vozila" required />
            <input type="number" name="year" placeholder="Godina proizvodnje" required />
            <input type="number" name="price" placeholder="Cena vozila" required />
            <input type="text" name="contact" placeholder="Kontakt (npr. broj telefona, email)" required />
            <input type="number" name="horsePower" placeholder="Broj konjskih snaga" required />
            <input type="text" name="motor" placeholder="Motor" required />
            <input type="text" name="fuel" placeholder="Gorivo" required />
            <textarea name="additional" cols="30" rows="10" placeholder="Dodatne informacije"></textarea>
            <div class="files-div">
                <label for="files-edit" class="btn">Dodaj sliku</label>
                <input type="file" id="files-edit" name="image2" accept=".img, .jpeg, .jpg, .png, .jfif" value="Dodaj sliku" />
            </div>
            <button type="submit" class="btn btn-primary">Sačuvaj</button>
        </form>
        <button type="button" class="btn btn-danger close-modal-btn" onclick="popEditAdModal(false)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"></path>
            </svg>
        </button>
    </div>

    <div id="editUserDataModal">
        <div class="editUserDataModal"><strong>Promenite podatke o vašem profilu</strong></div>
        <form method="POST" action="users.php">
            <label for="username">Trenutni username</label>
            <input type="text" name="username" id="username" value="<?php echo $_SESSION['username']; ?>">

            <label for="username">Trenutni email</label>
            <input type="text" name="email" id="email" value="<?php echo $_SESSION['email']; ?>">
            <button type="submit" class="btn btn-primary">Sačuvaj</button>
        </form>
        <hr>
        <div class="editUserDataModal"><strong>Promenite šifru</strong></div>
        <form method="POST" action="users.php">
            <label for="old-password">Stara šifra</label>
            <input type="password" name="old-password" id="old-password">

            <label for="new-password">Nova šifra</label>
            <input type="password" name="new-password" id="new-password">
            <button type="submit" class="btn btn-primary">Promeni šifru</button>
        </form>
        <button type="button" class="btn btn-danger close-modal-btn" onclick="closeEditUserDataModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"></path>
            </svg>
        </button>
    </div>
    <div id="deleteAdModal">
        <div class="question">
            Da li ste sigurni da želite da obrišete reklamu?
        </div>
        <div class='buttons'>
            <button type="button" class="btn btn-danger" onclick="deleteAd(); popDeleteAdModal(false)">Da</button>
            <button type="button" class="btn btn-dark" onclick="popDeleteAdModal(false)">Odustani</button>
        </div>
    </div>
    <div id="deleteProfileModal">
        <div class="question">
            Da li ste sigurni da želite da obrišete obrišete profil?
            <br>
            Ukoliko obrišete profil, sve vaše raklame će takođe biti obrisane
        </div>
        <div class='buttons'>
            <button type="button" class="btn btn-danger" onclick="deleteProfile(); popDeleteProfileModal(false)">Da</button>
            <button type="button" class="btn btn-dark" onclick="popDeleteProfileModal(false)">Odustani</button>
        </div>
    </div>
    <div id="background-overlay" onclick="closeModals()"></div>
    <script src="scripts/script.js"></script>
</body>

</html>