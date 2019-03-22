
function displayMessage() {
    $('.spinner-border').remove();
    const alertSuccess = document.createElement('div');
    alertSuccess.classList.add('alert', 'alert-success', 'mt-5');
    alertSuccess.innerHTML = "Data successfully imported.";
    (document.querySelector('.landing-jumbotron')).appendChild(alertSuccess);
}

function displayErrorMessage(error) {
    $('.spinner-border').remove();
    const alertWarning = document.createElement('div');
    alertWarning.classList.add('alert', 'alert-warning', 'mt-5');
    alertWarning.innerHTML = error;
    (document.querySelector('.landing-jumbotron')).appendChild(alertWarning);
}


function init() {
    $('#initBtn').remove();
    const spinnerBorder = document.createElement('div');
    spinnerBorder.classList.add('spinner-border', 'ml-3');
    (document.querySelector('.landing-jumbotron')).appendChild(spinnerBorder);

    fetch(BASE + 'api/init/', { credentials: 'include'})
        .then((response) => response.json())
        .then((responseData) => {
            console.log(responseData);
            if (responseData.error === 0) {
                displayMessage();
            } else {
                displayErrorMessage(responseData.error);
            }
        })
        .catch(error => console.warn(error));
}
