<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat de Location Véhicule Auto-Ecole</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>

        /** Définition de la taille de la page **/
        @page {
            margin: 100px 0px 80px 0px;
        }

        /** En-tête fixe **/
        header {
            position: fixed;
            top: -80px;
            left: 0px;
            right: 0px;
        }

        /** Pied de page fixe **/
        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 40px;

            text-align: center;
            font-size: 12px;
            border-top: 1px solid #000;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.3;
            margin: 20px;
        }

        h1,
        h2 {
            color: #2c3e50;
        }

        .underline {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .signature {
            margin-top: 50px;
        }
    </style>
</head>

<body>

        <header>
            <img src="{{ asset('images/header.png') }}" style="width: 100%; height: 100px" alt="">
        </header>

        <footer>
            <p>SMONI Auto-école : 62 Rue de la JARRY, 94300 VINCENNES, SIREN 915387013 RCS CRETEIL, TVA FR65915387013 NDA : <strong>11941318194</strong></p>
        </footer>

    <h1>CONTRAT DE LOCATION VEHICULE AUTO-ECOLE</h1>

    <p>Entre SMONI Auto Moto École, Société par Actions Simplifiée Unipersonnelle SASU au capital de 1.000 €</p>

    <p><strong>N° SIRET :</strong> 915 387 013 000 13 RCS CRETEIL</p>
    <p><strong>Adresse de l'établissement :</strong> 62 Rue de la Jarry 94300 Vincennes France</p>
    <p><strong>TÉL :</strong> 09 53 46 98 28 / 07 49 46 49 78 <strong>Courriel :</strong> contact@smoni.fr</p>
    <p><strong>Numéro d'identification à la TVA :</strong> FR65915387013</p>
    <p><strong>Exploité par :</strong> Madame BELLO Arike</p>
    <p><strong>Déclaration d'Activité enregistrée sous Numéro :</strong> 11941318194 auprès du Préfet de la Région Ile
        de France Délivré le 10 Avril 2025</p>
    <p><strong>Agréée sous le numéro :</strong> E2509400190 Délivrée par la préfecture du Val-de-Marne (Créteil) le 19
        juin 2025</p>
    <p><strong>Ci-après désigné (e) « l'école de conduite » ou « le loueur »</strong></p>

    <p>ET</p>

    <p>M/ Mme {{$user->lastname}} {{$user->firstname}} Né(e) le : {{date('d/m/Y',strtotime($info->birthdate))}} à
        {{$info->city}}</p>

    <p>Adresse : {{$info->address}}</p>
    <p>TEL : {{$user->phone}} Courriel : {{$user->email}}</p>
    <p>Nom et Prénom du représentant légal (pour les mineurs) :</p>
    <p>Adresse du représentant légal :</p>
    <p>TEL : Courriel :</p>
    <p>Nom et Prénom de l'accompagnateur :</p>
    <p>Adresse de l'accompagnateur :</p>
    <p>TEL : Courriel :</p>
    <p><strong>Ci-après désigné (e) « l'élève » ou « le locataire »</strong></p>

    <h2>Objet</h2>
    <p>Convenant à L'ARTICLE R211-3 de la loi MACRON, les Auto-Ecoles ont le droit de louer des véhicules doubles
        commandes dès lors qu'ils l'ont renseigné dans les registres de statuts d'activité auprès du tribunal des
        commerces, au même titre que les enseignes de location. Le contrat d'assurance pour l'activité sera probablement
        plus élevé que pour un véhicule dédié à l'enseignement ce qui justifie le contrat de location et l'accroissement
        tarifaire.</p>

    <h2>Prise d'effet du contrat et durée du contrat</h2>
    <p>Le présent contrat prend effet entre les parties le jour de sa signature jusqu'au retour du véhicule,
        c'est-à-dire du {{date('d/m/Y',strtotime($subscription->start_date))}} 08H00 jusqu'au
        {{date('d/m/Y',strtotime($subscription->end_date))}} à 20H00. Les tarifs aussi bien que les prix détaillés et
        les termes du contrat ne sont pas révisables durant toute la durée du contrat sauf modification législative ou
        réglementaire et en cas de résiliation du contrat qui ne peut intervenir que pendant la période de rétractation
        de 14 jours selon la réglementation en vigueur. Le contrat peut faire l'objet d'une prolongation par voie
        d'avenant sur commun accord des deux parties.</p>

    <h2>Conditions à respecter pour la location du véhicule double commande</h2>

    <h3>Condition du locataire</h3>
    <p>Si le locataire ne détient pas encore son permis, le locataire doit :</p>
    <ul>
        <li>Être âgé de 16 ans au minimum</li>
        <li>Être détenteur d'un livret d'apprentissage du permis de conduire le cas échéant, du formulaire de demande du
            permis de conduire dans les conditions fixées par l'arrêté du ministre chargé de la sécurité routière ou
            bien,</li>
        <li>Fournir l'attestation de fin de formation initiale (AFFI) figurant dans le livret d'apprentissage et validée
            par son auto-école.</li>
        <li>Être durant toute la durée de la location sous la surveillance d'un accompagnateur titulaire du permis B de
            plus de cinq (5) ans.</li>
    </ul>
    <p>Si le locataire est titulaire d'un permis, il doit présenter son permis et son accompagnateur doit remplir les
        mêmes conditions que tout accompagnateur (voir session suivante).</p>

    <h3>Condition de l'accompagnateur</h3>
    <p>Dans le cadre de la location, le loueur doit enregistrer au moins un accompagnateur, qui peut appartenir au cadre
        familial ou non du locataire. L'accompagnateur doit :</p>
    <ul>
        <li>Avoir son permis B en état de validité et ce depuis au moins 5 ans</li>
        <li>Ne pas avoir été condamné pour homicide et blessures involontaires.</li>
    </ul>

    <h2>Responsabilités du loueur</h2>
    <ul>
        <li>SMONI auto-école s'engage à mettre à la disposition du locataire, un véhicule double commande :</li>
        <li>Qui est en conformité avec ce qui est requis par la loi ou par le règlement français ;</li>
        <li>Dont l'entretien préconisé par le constructeur a été effectué et dont tous les équipements de sécurité sont,
            à sa connaissance, en parfait état, notamment les pneumatiques, les freins, les phares et feux, la
            direction, les ceintures de sécurité ainsi que la présence de tout matériel de sécurité obligatoire dans le
            pays d'immatriculation du véhicule ;</li>
        <li>Qui est à jour de son contrôle technique dans le pays d'immatriculation du véhicule ;</li>
        <li>Qui est assuré à l'année au minimum au tiers et par toute autre assurance obligatoire selon les lois du pays
            d'immatriculation du véhicule.</li>
        <li>Dont il est et demeure le propriétaire pendant toute la durée de la location ;</li>
        <li>SMONI auto-école doit renoncer à louer un véhicule dont il a connaissance d'un problème technique touchant à
            la sécurité du véhicule.</li>
    </ul>

    <h2>Responsabilités du Locataire</h2>
    <ul>
        <li>Le locataire s'engage à :</li>
        <li>Respecter les lois françaises en vigueur ;</li>
        <li>Utiliser le véhicule de façon raisonnable, rationnelle et responsable ;</li>
        <li>Rendre le véhicule dans un état propre à celui du début de la location ;</li>
        <li>Ne pas confier le véhicule à une tierce personne, sans l'accord préalable du loueur au risque d'une pénalité
            de 200€. Tout ajout de conducteurs doit faire l'objet de sa mention obligatoire au présent contrat de
            location ;</li>
        <li>Ne pas abandonner le véhicule après un accident ou une panne, et le garder sous sa responsabilité jusqu'à ce
            que le loueur puisse intervenir.</li>
    </ul>

    <h2>Autorisation d'encaissement du dépôt de garantie</h2>
    <p>L'autorisation de prélèvement de la caution est obligatoire et personnelle à chaque apprenti ou tuteur. La
        caution est ainsi déposée par chèque ou espèce, elle devient l'entière propriété du loueur en cas de sinistre,
        vol, et toutes dépenses à la responsabilité du locataire. Le chèque de caution sera restitué en intégralité deux
        semaines après la location si aucune contravention ne parvient au loueur. Le cas échéant, il sera déduit du
        montant de la contravention avant toute restitution de la différence au locataire.</p>

    <h2>Usages</h2>

    <h3>Interdits</h3>
    <p>Tout autre usage du véhicule que celui de l'apprentissage, ou de perfectionnement à la conduite est interdit.
        L'accompagnateur ne peut recevoir aucune rémunération de la part du locataire et/ou du loueur. S'il y a
        transaction financière entre l'apprenti et l'accompagnateur SMONI Auto-École se décharge de toute
        responsabilité. Il est formellement interdit de fumer, boire et manger dans le véhicule loué.</p>
    <p>En acceptant d'utiliser le véhicule, l'apprenti approuve que son état est conforme à la fiche « état des lieux du
        véhicule ».</p>
    <p>Tout transport d'une personne mineure est strictement interdit, seuls l'apprenti et le tuteur (accompagnateur)
        sont autorisés à bord du véhicule (sauf autorisation du loueur).</p>

    <h3>Carburant</h3>
    <p>Le carburant est à la charge du locataire, il doit restituer le véhicule avec la même quantité de carburant que
        lors de la prise du véhicule, si ce n'est pas le cas le loueur facturera un forfait de 10 litres de carburant
        qui viendront en déduction de la caution déposée.</p>

    <h2>Tarifs</h2>
    <table>
        <tr>
            <th>Produit</th>
            <th>Location avec accompagnateur externe</th>
        </tr>
        <tr>
            <td>Durée</td>
            <td>Toute la journée (8h -- 20h)</td>
        </tr>
        <tr>
            <td>Assurance de la journée</td>
            <td>Incluse</td>
        </tr>
        <tr>
            <td>Kilométrage</td>
            <td>Illimité</td>
        </tr>
        <tr>
            <td>Carburant</td>
            <td>Une mise à niveau obligatoire</td>
        </tr>
        <tr>
            <td>Tarif unique</td>
            <td>99€</td>
        </tr>
        <tr>
            <td>Pénalité de retard ou de conducteur non enregistré</td>
            <td>200€</td>
        </tr>
        <tr>
            <td>Chèque de caution à restituer</td>
            <td>500€</td>
        </tr>
        <tr>
            <td>Franchise non récupérable à la fin du contrat</td>
            <td>150€</td>
        </tr>
    </table>

    <h2>État des lieux du véhicule</h2>
    <p>SMONI-AUTO-ÉCOLE est une SASU immatriculée au tribunal de Créteil qui met à disposition du locataire, un véhicule
        double commande. Lors de la remise du véhicule, un état des lieux écrit du véhicule est établi entre le loueur
        et le locataire. Le véhicule devra être restitué dans le même état que lors de sa mise à disposition au
        locataire avec le même niveau de jauge de carburant. Toutes détériorations sur le véhicule constatées pendant
        les heures d'apprentissage seront aux frais du locataire. Les contraventions (amendes, PV, excès de vitesse
        etc.) seront à la charge du locataire s'ils interviennent dans la période de location.</p>

    <p>Date : __/ __/ 20__ Réalisé par : ___________ Date de validité du CT : __/ __/ 20__</p>
    <p>Boite: □ Manuelle □ Auto modèle du véhicule : ___________ Immatriculation : _________________</p>
    <p>Date de dernière vidange : __/ __/ 20____ Date de la prochaine révision : __/ __/ 20____</p>

    <table>
        <tr>
            <th>Prise en charge</th>
            <th>Km du véhicule</th>
            <th>Restitution</th>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Aspect Intérieur</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Tableau de bord</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Frein à main</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Pédales</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Rétroviseur</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Totaliseur Kilométrique</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Portières AVG, AVD, ARG, ARD</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Vitres AVG, AVD, ARG, ARD</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Ceintures de sécurité</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Sièges</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Plafond intérieur</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Coffre</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
        <tr>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
            <td>Pneumatique AVG, AVD, ARG, ARD</td>
            <td>☐Neuf / ☐Bon / ☐Correct / ☐Mauvais<br>Commentaire :</td>
        </tr>
    </table>

    <h3 class="underline">Prise en charge du véhicule</h3>
    <h3 class="underline">Restitution du véhicule</h3>

    <p>Indiquez les bosses et les rayures par : O</p>
    <p>Commentaires : Commentaires :</p>

    <h2>Assurance</h2>
    <p>Le véhicule bénéficie d'une assurance « tous risques » souscrite par SMONI Auto-École et qui limite la conduite
        du véhicule au territoire français ou tout autre territoire où s'exécute le contrat. Cette assurance comporte
        une franchise qui s'élève à 500 euros en cas de sinistre responsable ou non avec ou sans tiers identifié raison
        de la caution de 500€ déposé par le locataire.</p>
    <p>SMONI Auto-École propose également une franchise de 150€, valable une fois par forfait et renouvelable à chaque
        nouveau forfait le cas échéant.</p>
    <p>Si le locataire opte pour le paiement de la Franchise (150€), il est exempté des 500€ de caution même en cas
        d'accident responsable.</p>

    <h2>Panne et accident</h2>
    <p>Toute panne et tout accident doit être signalé à SMONI Auto-École avant d'effectuer quelque démarche que ce soit.
        Le locataire doit par la suite suivre toutes les instructions que lui aura communiquées SMONI Auto-École. Dans
        le cas où le locataire est responsable d'un accident, son chèque de caution devient un acquis et encaissé par
        SMONI Auto-École. Une lettre de notification lui sera envoyé à ce sujet.</p>
    <p>Toute dépense, directement ou indirectement générée par la réparation du véhicule sera pris en charge par
        l'assurance du véhicule. Aucun autre frais occasionné par la réparation du véhicule, ne pourra être imputé au
        locataire qui s'était acquitté de la caution ou de la franchise.</p>
    <p>Si toute fois le chèque déposé manque d'approvisionnement, alors ce cas occasionnera des frais de gestion
        forfaitaire de 25 euros en plus des 500 € dû. SMONI Auto-École se réserve le droit de procéder par tous les
        moyens en sa disposition pour recouvrir le montant de la facturation ou de la refacturation adressée au
        préalable au locataire.</p>
    <p>Dans le cas du non signalement de l'évènement à SMONI Auto-École, et ou qu'il prenne l'initiative de résoudre
        l'incident sans tenir informer SMONI Auto-École, il sera exposé à de fortes amendes, indemnités et dommages et
        intérêts vis-à-vis de SMONI Auto-École. Il sera donc tenu responsable pour toute gêne ou problème occasionné, du
        fait de son initiative.</p>

    <h2>Rétractation</h2>
    <p>Il pourra y être mis fin par chacune des parties à tout moment en adressant un courrier recommandé en respectant
        un préavis de 14 jours de réflexion. La période de rétractation n'a plus raison d'être dès lors que le client a
        pris le véhicule ou a démarré ses séances d'apprentissage avec l'accompagnateur.</p>

    <h2>Résiliation</h2>
    <p>Toute annulation ou modification de réservation, doit être effectuée au plus tard 48 heures avant la session de
        location, préalablement réservée, dans le cas contraire l'heure sera due de plein droit à SMONI Auto-École, quel
        que soit le motif. Si le locataire n'honore pas ses réservations plus de trois fois, SMONI Auto-École, se
        réserve le droit de lui résilier l'adhésion à nos services.</p>
    <p>Les forfaits ont une durée de validité de 1 mois. Ils ne sont ni échangeables, ni remboursables. SMONI Auto-École
        peut mettre fin unilatéralement au contrat, sans remboursement des heures restantes et sans préavis, dans la
        mesure où l'accompagnateur ou le locataire commet une faute de non-respect de l'une des clauses du contrat de
        location. Et ce, en demandant réparation de l'ensemble des préjudices passés, présents et futurs.</p>

    <h2>Pénalités</h2>
    <p>En cas de retour du véhicule à une date ultérieure à la date du contrat, le locataire devra à l'auto-école</p>
    <ul>
        <li>Si le retour se fait entre 20H et 21H :
            <ul>
                <li>L'élève devra s'acquitter d'une pénalité de 10€ par ¼ d'heure de retard</li>
            </ul>
        </li>
        <li>Au-delà de 21H, le retour de la voiture se fera le lendemain, l'apprenti devra
            <ul>
                <li>Payer le nombre de jour de retard (si le retour était prévu pour le 20/01/25 à 20h et qu'il rend la
                    voiture le 21/01/25 à 8h, il devra régler toute la journée du 21/05/25)</li>
                <li>Il devra en plus de cela s'acquitter d'une pénalité de 200€ par jour de retard</li>
            </ul>
        </li>
    </ul>
    <p>Au-delà de 24H de retard et sans nouvelle du locataire, des poursuites pourront être engagées à son encontre,
        sans préavis. Cette pénalité financière ne s'applique pas en cas de panne, sinistre ou vol si l'apprenti
        prévient SMONI Auto-École durant la durée de sa location.</p>

    <h2>Clause en cas de litige</h2>
    <p>Tout litige pouvant naître de l'exécution du présent contrat relèvera de la compétence du tribunal de commerce de
        Créteil. En cas de désaccord ou litige entre les parties, le présent contrat est soumis au droit français ou au
        droit de tout pays où l'exécution du contrat est effectuée. A défaut de solution amiable, l'élève peut recourir
        gratuitement, dans les conditions prévues aux articles L. 612-1 et suivants et R. 612-1 et suivants du code de
        la consommation à un médiateur de la consommation en vue de la résolution amiable de tout litige l'opposant à
        SMONI Auto-École, relatif au présent contrat (nom et coordonnées du médiateur) : ___________________________.
        Avant de saisir le médiateur, l'apprenant doit avoir adressé au préalable une réclamation écrite à SMONI
        Auto-École. Il doit saisir le médiateur dans le délai d'un an maximum à compter de sa réclamation écrite.</p>

    <p>Fait à Vincennes, Le {{date('d/m/Y',strtotime($subscription->created_at))}} en deux exemplaires originaux.</p>

    <div class="signature">
    </div>

    <table style="width: 100%; border:1px solid transparent">
        <td style="width: 25%;border:1px solid transparent">
            <p>Signature du locataire précédée de la mention <strong>"lu et approuvé"</strong></p>
            <br>
            <br>
        </td>
        <td style="width: 25%;border:1px solid transparent">
            <p>Signature du représentant légal de l'élève mineur, le cas échéant précédée de la mention <strong>"lu et
                    approuvé"</strong></p>
            <br>
            <br>
        </td>
        <td style="width: 25%;border:1px solid transparent">
            <p>Signature du responsable de SMONI et cachet précédé de la mention <strong>"lu et approuvé"</strong></p>
            <br>
            <br>
        </td>

        <td style="width: 25%;border:1px solid transparent">
            <p>Signature du l'accompagnateur précédée de la mention <strong>"lu et approuvé"</strong></p>
            <br>
            <br>
        </td>
    </table>
</body>

</html>