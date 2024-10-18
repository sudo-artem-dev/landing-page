<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" type="image/png" href="../../public/img/domaine.png">
</head>
<body>
    <header>
        <h1>Bienvenue sur notre Landing Page</h1>
    </header>
    <main>
        <section>
            <div class="container">
                <h1>S'inscrire</h1>
                <form action="../back/modeles/registration.php" id="registrationForm">
                    <div class="form-group">
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" >
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" >
                    </div>
                    <div class="genre-container">
                        <label for="homme">Genre :</label><br>
                        <input type="radio" id="homme" name="genre" value="Homme">
                        <label for="homme">Homme</label>
                        <input type="radio" id="femme" name="genre" value="Femme">
                        <label for="femme">Femme</label>
                    </div>
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email" >
                    </div>
                    <div class="form-group">
                        <label for="date">Date de naissance :</label>
                        <input type="date" id="date" name="date" >
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone :</label>
                        <input type="tel" id="telephone" name="telephone" >
                    </div>
                    <div class="form-group">
                        <label for="pays">Pays :</label>
                        <input type="text" id="pays" name="pays" >
                    </div>
                    <div class="form-group">
                        <label for="question">Question à poser :</label>
                        <textarea id="question" name="question" minlength="15"></textarea>
                    </div>
                    <button type="submit" id="submitForm">Envoyer</button>
                </form>
                <div class="messageError" id="errorContainer"></div>
                <div class="messageSuccess" id="successContainer"></div>

            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Landing Page. All rights reserved.</p>
    </footer>
    <script src="./app.js"></script>

</body>
</html>
