<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel pour de rendez-vous</title>
    <style>
        /* Styles généraux pour le corps de l'e-mail */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #F8F5FF; /* Couleur de fond pâle */
            color: #333;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Conteneur principal de l'e-mail */
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            padding: 30px; /* Padding interne pour le contenu */
            box-sizing: border-box; /* Inclut le padding dans la largeur/hauteur */
        }

        /* En-tête / Logo */
        .header {
            text-align: center;
            padding-bottom: 25px; /* Espace sous le logo */
        }

        .header img {
            max-width: 150px; /* Taille approximative du logo */
            height: auto;
        }

        /* Titre de l'e-mail */
        .email-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 25px;
            text-align: left; /* Alignement du titre */
        }

        /* Paragraphes de contenu */
        .content-paragraph {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
            color: #555; /* Couleur de texte légèrement plus douce */
        }

        /* Bouton */
        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            background-color: #8A64FF; /* Couleur du bouton violet/bleu */
            color: #ffffff !important; /* Force la couleur du texte blanc */
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none; /* Supprime le soulignement des liens */
            font-weight: bold;
            font-size: 16px;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        /* Texte d'information sous le bouton */
        .info-text {
            font-size: 14px;
            line-height: 1.6;
            color: #777;
            margin-top: 25px;
        }

        /* Signature */
        .signature {
            font-size: 15px;
            font-weight: bold;
            margin-top: 30px;
            color: #333;
        }

        /* Assurer la responsivité de base pour les petits écrans */
        @media only screen and (max-width: 620px) {
            .email-container {
                width: 90% !important;
                margin: 20px auto !important;
                padding: 20px !important;
            }
            .email-title {
                font-size: 20px !important;
            }
            .content-paragraph, .info-text, .signature {
                font-size: 14px !important;
            }
            .button {
                padding: 12px 25px !important;
                font-size: 15px !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMONI Auto-Moto École">
        </div>

        <h1 class="email-title">Rappel pour de rendez-vous</h1>

        <p class="content-paragraph">Hello 👋 {{ $data->firstname }}</p>
        <p class="content-paragraph">Nous vous rappellons que vous aviez un rendez-vous pour dans les prochain jours. Veuillez vous connecter afin de prendre plus d'informations sur ce rendez-vous</p>
        <p class="content-paragraph">Si ce n'était pas vous, ignorez simplement cet email.</p>

        <p class="info-text">Pour plus de renseignement, n'hésitez pas à passer par notre centre d'aide.</p>
        <p class="info-text">À très vite sur SMONI !</p>

        <p class="signature">L'équipe SMONI</p>
    </div>
</body>
</html>