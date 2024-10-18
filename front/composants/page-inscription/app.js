document.getElementById('submitForm').addEventListener('click', function(event) {
    event.preventDefault(); // empêche le formulaire de se soumettre normalement

    var form = document.getElementById('registrationForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../../back/modeles/registration.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Traitement de la réponse JSON
            var response = JSON.parse(xhr.responseText);
            if (response.error) {
                // Afficher le message d'erreur sur la page
                displaySuccess('');
                displayError(response.error);
            } else if (response.success) {
                form.reset();
                // Afficher le message de succès sur la page
                displayError('');
                displaySuccess(response.success);
            }
        }
    };
    xhr.send(new URLSearchParams(formData));
});

// Fonction pour afficher les messages d'erreur sur la page
function displayError(errorMessage) {
    // Supprime les messages d'erreur précédents s'il y en a
    var errorContainer = document.getElementById('errorContainer');
    while (errorContainer.firstChild) {
        errorContainer.removeChild(errorContainer.firstChild);
    }

    // Crée et affiche le nouveau message d'erreur
    var errorElement = document.createElement('p');
    errorElement.textContent = errorMessage;
    errorContainer.appendChild(errorElement);


}
// Fonction pour afficher le message de succès sur la page
function displaySuccess(successMessage) {
    var successContainer = document.getElementById('successContainer');
    while (successContainer.firstChild) {
        successContainer.removeChild(successContainer.firstChild);
    }

    // Crée et affiche le message de succès
    var successElement = document.createElement('p');
    successElement.textContent = successMessage;
    document.getElementById('successContainer').appendChild(successElement);
}
