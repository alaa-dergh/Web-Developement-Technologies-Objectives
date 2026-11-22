
<?php

class AuthView {

    private function header($title) {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, initial-scale=1.0">
            <title><?= htmlspecialchars($title) ?></title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
        <?php
    }

    private function footer() {
        ?>
        </body>
        </html>
        <?php
    }

    // LOGIN VIEW
    public function showLogin($message = "") {

        $this->header("Connexion - ESI");
        ?>

        <div class="container">

            <h2>Connexion</h2>

            <?php if ($message !== ""): ?>
                <div class="msg error">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">

                <label>Email ESI</label>
                <input
                    type="email"
                    name="email"
                    placeholder="nom@esi.dz"
                    required
                >

                <label>Mot de passe</label>
                <input
                    type="password"
                    name="password"
                    required
                >

                <button type="submit">
                    Se connecter
                </button>

            </form>

            <div class="link">
                Pas encore inscrit ?
                <a href="register.php">S'inscrire</a>
            </div>

        </div>

        <?php
        $this->footer();
    }

    // REGISTER VIEW
    public function showRegister($message = "") {

        $this->header("Inscription - ESI");
        ?>

        <div class="container">

            <h2>Inscription</h2>

            <?php if ($message !== ""): ?>
                <div class="msg">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">

                <label>Nom</label>
                <input
                    type="text"
                    name="name"
                    required
                >

                <label>Email ESI</label>
                <input
                    type="email"
                    name="email"
                    placeholder="nom@esi.dz"
                    required
                >

                <label>Mot de passe</label>
                <input
                    type="password"
                    name="password"
                    required
                >

                <button type="submit">
                    S'inscrire
                </button>

            </form>

            <div class="link">
                Déjà inscrit ?
                <a href="login.php">Se connecter</a>
            </div>

        </div>

        <?php
        $this->footer();
    }
}

?>
