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

        /* Pied de page */
        .footer {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #8A64FF;
            text-align: center;
        }

        .footer-logo {
            margin-bottom: 15px;
        }

        .footer-logo img {
            max-width: 120px;
            height: auto;
        }

        .footer-title {
            font-size: 18px;
            font-weight: bold;
            color: #8A64FF;
            margin-bottom: 8px;
        }

        .footer-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            font-style: italic;
        }

        .footer-contact {
            font-size: 14px;
            color: #555;
            line-height: 1.5;
            margin-bottom: 8px;
        }

        .footer-address {
            font-size: 13px;
            color: #777;
            margin-bottom: 8px;
        }

        .footer-agreement {
            font-size: 12px;
            color: #999;
            font-weight: bold;
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
            .footer-title {
                font-size: 16px !important;
            }
            .footer-tagline, .footer-contact, .footer-address {
                font-size: 13px !important;
            }
            .footer-agreement {
                font-size: 11px !important;
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

        <!-- Pied de page -->
        <div class="footer">
            <div class="footer-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMONI Auto-Moto École">
            </div>
            
            <div class="footer-title">SMONI auto-école</div>
            <div class="footer-tagline">Votre Apprentissage en tout sérénité</div>
            
            <div class="footer-contact">
                <strong>Téléphone :</strong> 0953469828 - 0749464978
            </div>
            
            <div class="footer-address">
                62 rue de la jarry, 94300 Vincennes
            </div>
            
            <div class="footer-agreement">
                N° d'agrément E 2509400190
            </div>
        </div>
    </div>
</body>
</html>