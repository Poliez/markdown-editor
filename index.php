<?php
    require_once "./php/util/cookieManager.php";
    require_once "./php/util/navigationUtil.php";
    require_once "./php/util/sessionUtil.php";
    require_once "./php/util/markdownDb.php";

    if ($username = $cookieManager->checkUserCookie()){
        session_start();

        setSession(
            $username, 
            getUserId($username)
        );

        goToEditor();
    }

?>
<!doctype html>
<html>

<head>
    <!-- Consente ai legacy browsers di applicare stili ai tag sconosciuti -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
    <title>Real-time Markdown Editor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <script type="text/javascript" src="js/login.js"></script>
</head>

<body>

    <div class="center">
        <h1>Benvenuto a Real-time Markdown Editor!</h1> <br/><br/><br/><br/>
        <button onclick="showLoginForm()" class="mainbtn">Accedi</button>
        <button onclick="showRegistrationForm()" class="mainbtn">Registrati</button>
    </div>

    <!-- Login Modal -->
    <div id="loginform" class="modal">
        <form class="modal-content animate" action="php/login.php" method="post">
            <div class="container">
                <h3 class="loginHead">Effettua il Login</h3>
                <span onclick="hideLoginForm()" class="close" title="Chiudi">&times;</span>
                <input id="userinput" type="text" placeholder="Nome Utente" name="username" required />
                <input type="password" placeholder="Password" name="password" required />
                
                <ul class="horizontal">
                    <li class="horizontal"><input type="checkbox" checked="checked" name="remember" /></li>
                    <li class="horizontal"><label>Ricordati di me</label></li>
                </ul>

                <button type="submit">Login</button>
            </div>
            <div class="container" style="background-color:#f1f1f1">
                <button type="button" onclick="hideLoginForm()" class="cancelbtn">Annulla</button>
            </div>
        </form>
    </div>

    <!-- Registration Modal -->
    <div id="registrationform" class="modal">
        <form class="modal-content animate" action="php/registration.php" method="post">
            <div class="container">
                <h3 class="loginHead">Registrati!</h3>
                <span onclick="hideRegistrationForm()" class="close" title="Chiudi">&times;</span>
                <input id="nameinput" type="text" placeholder="Nome" name="name" required />
                <input type="text" placeholder="Cognome" name="surname" required />
                <input type="text" placeholder="Nome Utente" name="username" required />
                <input type="text" placeholder="Email" name="email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" title="Devi inserire un indirizzo email valido."required />
                <input id="password" type="password" placeholder="Password" name="password" pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!-/:-@\[-`{-~])[A-Za-zÀ-ÿ\d!-/:-@\[-`{-~]{8,32}" title="La password deve contenere da 8 a 32 caratteri, tra cui almeno una lettera maiuscola, almeno un carattere speciale e almeno un numero." required />
                <input id="confirm" type="password" placeholder="Conferma Password" name="confirm" required />
                <button type="submit">Registrati</button>
            </div>
            <div class="container" style="background-color:#f1f1f1">
                <button type="button" onclick="hideRegistrationForm()" class="cancelbtn">Annulla</button>
            </div>
        </form>
    </div>

</body>

</html>
