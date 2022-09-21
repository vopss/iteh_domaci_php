const adsContainerElement = document.querySelector('.ads-container')
const sortSelect = document.querySelector('#sort-select')
let ads = [];
const usernameDiv = document.getElementById('username');
let selectedAd = null;
//filter elements
const filters = {
    brand: document.querySelector("#filter-brand"),
    priceFrom: document.querySelector("#filter-priceFrom"),
    priceTo: document.querySelector("#filter-priceTo"),
    yearFrom: document.querySelector("#filter-yearFrom"),
    yearTo: document.querySelector("#filter-yearTo"),
}

//get user data from query params
// const urlSearchParams = new URLSearchParams(window.location.search);
// const params = Object.fromEntries(urlSearchParams.entries());

// const username = params.username;
// const email = params.email;

function sendRequest(method, url, callback, body, queryParams) {
    let xhr = new XMLHttpRequest();
    xhr.open(method, url, true)

    //TODO: implement query params
    xhr.onload = function () {
        if (xhr.status == 200) {
            console.log("Successful request");
        } else {
            console.log(`Error with xml http request: ${xhr}`)
        }
    }

    if (method == "POST") xhr.send(body);
    else xhr.send();
}

const logoutUser = () => {
    location.href = "logout.php";
}

const addNewAd = () => {
    setOverlay(true);
    setAddNewAdModal(true);
}

function setAddNewAdModal(active) {
    const modal = document.querySelector('#addNewAdModal');
    if (active) {
        modal.classList.add('active');
    } else {
        modal.classList.remove('active')
    }
}


function setEditUserDataModal(active) {
    const modal = document.querySelector('#editUserDataModal');
    if (active) {
        modal.classList.add('active');
    } else {
        modal.classList.remove('active')
    }
}

function setDeleteAdModal(active) {
    const modal = document.querySelector('#deleteAdModal');
    if (active) {
        modal.classList.add('active');
    } else {
        modal.classList.remove('active')
    }
}

function popDeleteAdModal(bool, adId) {
    selectedAd = adId;
    setOverlay(bool);
    setDeleteAdModal(bool);
}

function closeAddNewAdModal() {
    setOverlay(false);
    setAddNewAdModal(false);
}

function editUserData() {
    setOverlay(true);
    setEditUserDataModal(true);
}

function deleteAd() {
    let adId = selectedAd
    let xhr = new XMLHttpRequest();
    xhr.open("DELETE", "ads.php", true);
    xhr.onload = function () {
        if (xhr.status == 200) {
            if (xhr.responseText == "Success") {
                alert("Uspešno ste obrisali oglas")
                deleteAdElement(adId);
            } else {
                alert(`Greška prilikom birsanja oglasa: ${xhr.responseText}`)
            };
        }
    }
    xhr.send(JSON.stringify({ id: adId }))
}

function deleteAdElement(adId) {
    const adElement = document.querySelector(`[data-id="${adId}"]`)
    adElement.remove();
}

function closeEditUserDataModal() {
    setOverlay(false);
    setEditUserDataModal(false);
}

function closeModals() {
    setOverlay(false);
    setAddNewAdModal(false);
    setEditUserDataModal(false);
    setDeleteAdModal(false);
    setDeleteProfileModal(false);
    setEditAdModal(false);
}

function setDeleteProfileModal(active) {
    const modal = document.querySelector('#deleteProfileModal');
    if (active) {
        modal.classList.add('active');
    } else {
        modal.classList.remove('active')
    }
}

function popDeleteProfileModal(bool) {
    setOverlay(bool);
    setDeleteProfileModal(bool);
}

function popEditAdModal(bool, adId) {
    setOverlay(bool);
    setEditAdModal(bool, adId);
}

function setEditAdModal(active, adId) {
    const modal = document.querySelector('#editAdModal');


    if (active) {
        let myAd = null;
        for (ad of ads) {
            if (ad.id == adId) {
                myAd = ad;
                break;
            }
        }
        modal.classList.add('active');

        const myForm = modal.querySelector('form');
        for (prop in myAd) {
            const inputEl = myForm[prop];
            if (inputEl && inputEl.type != 'file') {
                inputEl.value = myAd[prop];
            }
        }
    } else {
        modal.classList.remove('active')
    }
}

function deleteProfile() {
    let xhr = new XMLHttpRequest();
    xhr.open("DELETE", "users.php", true);
    xhr.onload = function () {
        if (xhr.status == 200) {
            console.log(xhr.responseText);
            if (xhr.responseText == "Success") {
                alert("Uspešno ste obrisali profil");
                location.href = "login.php";
            } else {
                alert(`Greška prilikom birsanja profila: ${xhr.responseText}`)
            };
        }
    }
    xhr.send()
}

function setOverlay(active) {
    const overlay = document.querySelector('#background-overlay');
    if (active) {
        overlay.classList.add('active');
    } else {
        overlay.classList.remove('active')
    }
}

function getAds() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "ads.php?getAll", true);
    xhr.onload = function () {
        if (xhr.status == 200) {
            let response = xhr.responseText;
            try {
                response = JSON.parse(response);
                ads = response;
                sortAds();
            } catch (error) {
                console.log(`Error parsing response from server: ${error}`);
                return;
            }
        }
    }
    xhr.send()
}

function createNewAddElement(ad) {
    const username = usernameDiv.innerHTML;
    const yourAd = username == ad.username;
    const elementTemplate = `
    <div class="ad-div ${yourAd ? 'your-ad' : ''}" data-id='${ad.id}'>
                <div class="img-div">
                    <picture>
                        <img src="resources/images/ad_images/${ad.image}" onerror="this.onerror=null; this.src='resources/images/ad_images/default-car.png'" alt="Car" style="width:auto;">
                    </picture>
                </div>
                <div class="vr-div"></div>
                <div class="ad-info-div">
                    <div class="ad-title">
                        <div>${ad.title}</div>
                        ${yourAd ?
            `<button type="button" class="btn btn-success" onclick="popEditAdModal(true, ${ad.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"></path>
                            </svg>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="popDeleteAdModal(true, ${ad.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                            </svg>
                        </button>` : ''}
                    </div>
                    <div class="ad-basic-info-div">
                        <button type="button" class="btn">${ad.brand}</button>
                        <button type="button" class="btn">${ad.model}</button>
                        <button type="button" class="btn">${ad.year}</button>
                    </div>
                    <div class="ad-detailed-info-div">
                        <div>Konjske snage: ${ad.horsePower}</div>
                        <div>Motor: ${ad.motor}</div>
                        <div>Gorivo: ${ad.fuel}</div>
                        <div class="owner-div">Vlasnik: ${ad.username}</div>
                        <div class="contact-div">Kontakt: ${ad.contact}</div>
                        <button type="button" class="btn btn-danger price-div">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tag-fill" viewBox="0 0 16 16">
                                <path d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1H2zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                            </svg>
                            <div>
                                ${ad.price}€
                            </div>
                        </button>
                        <div class="additional-info-div">Dodatne informacije: ${ad.additional}</div>
                        <button type="button" class="date-div btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-week-fill" viewBox="0 0 16 16">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zM9.5 7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm3 0h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zM2 10.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"></path>
                            </svg>
                            <div>
                                ${ad.date_created}
                            </div>
                        </button>
                    </div>
                </div>
            </div>`

    adsContainerElement.insertAdjacentHTML('beforeend', elementTemplate)
}
getAds();

function sortAds() {
    const sortingParametar = sortSelect.value;

    const sortOrder = document.querySelector('input[name="sortOrderRadio"]:checked').value;

    if (sortingParametar == 'date_created') {
        ads.sort((a, b) => {
            aValue = new Date(a[sortingParametar])
            bValue = new Date(b[sortingParametar])
            if (sortOrder == 'asc') {
                return aValue < bValue ? -1 : 1;
            } else if (sortOrder == 'desc') {
                return aValue > bValue ? -1 : 1;
            } else {
                return 0;
            }
        })
    } else {
        ads.sort((a, b) => {
            aValue = parseInt(a[sortingParametar])
            bValue = parseInt(b[sortingParametar])
            if (sortOrder == 'asc') {
                return aValue < bValue ? -1 : 1;
            } else if (sortOrder == 'desc') {
                return aValue > bValue ? -1 : 1;
            } else {
                return 0;
            }
        })
    }
    rerenderAds();
}

function rerenderAds() {
    adsContainerElement.innerHTML = "";
    ads.forEach(ad => createNewAddElement(ad));
}

function filterAds() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", 'ads.php?filter', true)

    //TODO: implement query params
    xhr.onload = function () {
        if (xhr.status == 200) {
            let response = xhr.responseText;
            try {
                response = JSON.parse(response);
                if (response) {
                    ads = response;
                } else {
                    ads = [];
                }
                sortAds();
            } catch (error) {
                console.log(error);
            }
        } else {
            console.log(`Error with xml http request: ${xhr}`)
        }
    }

    var formData = new FormData();
    formData.append("brand", filters.brand.value);
    formData.append("priceFrom", filters.priceFrom.value);
    formData.append("priceTo", filters.priceTo.value);
    formData.append("yearFrom", filters.yearFrom.value);
    formData.append("yearTo", filters.yearTo.value);
    xhr.send(formData);
}
