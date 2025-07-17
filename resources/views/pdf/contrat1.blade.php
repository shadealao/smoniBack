<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat d'Enseignement à la Conduite - SMONI</title>
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
        h2,
        h3 {
            color: #333;
        }

        .underline {
            text-decoration: underline;
        }

        .checkbox {
            margin-right: 5px;
        }
    </style>
</head>

<body>

        <header>
            <img src="{{ asset('images/header.png') }}" style="width: 100%; height: 70px" alt="">
        </header>

        <footer>
            <p>SMONI Auto-école : 62 Rue de la JARRY, 94300 VINCENNES, SIREN 915387013 RCS CRETEIL, TVA FR65915387013 NDA : <strong>11941318194</strong></p>
        </footer>

    <h1>CONTRAT D'ENSEIGNEMENT À LA CONDUITE</h1>
    <h2>AUTO MOTO</h2>

    <p>
        <input type="checkbox" class="checkbox"> Traditionnelle B
        <input type="checkbox" class="checkbox"> AAC
        <input type="checkbox" class="checkbox"> CS
        <input type="checkbox" class="checkbox"> Passerelle Automatique-Manuelle
    </p>
    <p>
        <input type="checkbox" class="checkbox"> A2
        <input type="checkbox" class="checkbox"> AM
        <input type="checkbox" class="checkbox"> BSR
        <input type="checkbox" class="checkbox"> 125 CM3
        <input type="checkbox" class="checkbox"> Passerelle Moto
        _________________________
    </p>

    <p>Entre <strong>SMONI Auto Moto École</strong>, Société par Actions Simplifiée Unipersonnelle SASU au capital de
        1.000 €</p>

    <p><strong>N° SIRET :</strong> 915 387 013 000 13 RCS CRETEIL</p>
    <p><strong>Adresse de l'établissement :</strong> 62 Rue de la Jarry 94300 Vincennes France</p>
    <p><strong>TÉL :</strong> 09 53 46 98 28 <strong>Portable :</strong> 07 49 46 49 78 <strong>Courriel :</strong>
        contact@smoni.fr</p>
    <p><strong>Numéro d'identification à la TVA :</strong> FR65 915387013</p>
    <p><strong>Exploité par :</strong> Madame BELLO Arike</p>
    <p><strong>Déclaration d'Activité enregistrée sous Numéro : 11941318194</strong> auprès du Préfet de la Région Ile
        de France</p>
    <p>Agréée sous le numéro E2509400190 Délivrée par la préfecture du Val-de-Marne (Créteil) le 19 juin 2025</p>
    <p><strong>Ci-après désigné (e) « l'école de conduite »</strong></p>

    <h3>ET</h3>

    <p>M/ Mme {{$user->lastname}} {{$user->firstname}} Né(e) le : {{date('d/m/Y',strtotime($info->birthdate))}} à {{$info->city}}</p>
    <p>Adresse : {{$info->address}}</p>
    <p>Nom et Prénom du représentant légal (pour les mineurs) : ___________________________</p>
    <p>Adresse du représentant légal : ________________________________________________________</p>
    <p>TEL : {{$user->phone}} Courriel : {{$user->email}}</p>
    <p><strong>Ci-après désigné (e) « l'élève »</strong></p>

    <h3>Prise de connaissance préalable de l'élève</h3>
    <p>L'établissement a l'obligation d'évaluer le niveau du candidat avant son entrée en formation. En application de
        l'article L. 213-2 du code de la route, ledit contrat est conclu après une évaluation préalable du candidat,
        soit dans nos locaux soit dans un véhicule. Cette évaluation permettra ainsi à SMONI de déterminer le nombre
        prévisionnel d'heures de formation théorique et/ou pratique nécessaires à l'élève pour sa formation. Le volume
        de formation prévu est susceptible d'être révisé par la suite, d'un commun accord des deux parties. L'évaluation
        de départ de l'élève a été réalisée par : ________________________ le : __/__/____ avec pour moyen d'évaluation
        utilisé : voiture, le cas échéant sous la responsabilité de Mme/M. : ________________________ missionné par
        SMONI et titulaire de l'autorisation d'enseigner numéro : ________________________ délivrée le ___/___/____.
        Après cette dernière, il en ressort l'élaboration d'une fiche d'évaluation annexée au contrat.</p>
    <p>A la fin de cette évaluation, le nombre d'heures prévisionnel de formation pratique est de {{$subscription->hour}} heures.</p>

    <h2>A. OBJET DU CONTRAT</h2>
    <p>Conformément aux articles L. 213-2 et R. 213-3 du code de la route et à l'arrêté du 22 décembre 2009 relatif à
        l'apprentissage de la conduite des véhicules à moteur de la catégorie B, le présent contrat a pour but d'établir
        aussi bien les conditions que les modalités de l'enseignement théorique et/ou pratique, de la conduite des
        véhicules à moteur de la catégorie B, A2, AM, BSR, 125 CM3 et de la sensibilisation sur la sécurité routière
        ainsi que des différentes passerelles. Le choix de la catégorie de formation incombe à l'élève.</p>
    <p>Plus précisément, le présent contrat a pour objet selon le choix préalable de l'élève :</p>
    <p><input type="checkbox" class="checkbox"> Une mise en relation élève-enseignants indépendants diplômés d'état</p>
    <p><input type="checkbox" class="checkbox"> L'Enseignement de la conduite et sécurité routière selon le forfait
        choisi.</p>
    <p><input type="checkbox" class="checkbox"> Locations de véhicules avec double-commande</p>
    <p><input type="checkbox" class="checkbox"> Vente de forfaits des séances de conduite auto-moto en ligne et/ou en
        présentiel.</p>

    <h2>B. PRISE D'EFFET ET DUREE DU CONTRAT</h2>
    <p>Le présent contrat prend effet entre les parties le jour de sa signature pour une durée de {{$service->times}} mois, c'est-à-dire
        jusqu'au {{date('d/m/Y',strtotime($subscription->end_date))}}
        révisables durant toute la durée du contrat sauf modification législative ou réglementaire et en cas de
        résiliation du contrat qui ne peut intervenir que pendant la période de rétractation de 14 jours selon la
        règlementation en vigueur.</p>
    <p>Le contrat peut faire l'objet d'une prolongation par voie d'avenant à l'initiative de l'une ou l'autre des
        parties.</p>

    <h2>C. PROGRAMME ET DÉROULEMENT DE LA FORMATION</h2>
    <p>SMONI s'engage à délivrer à l'élève, une formation théorique et pratique conforme aux dispositions législatives
        et réglementaires en vigueur. L'auto-école établit au nom de l'élève une fiche de suivi de formation qui sera
        conservée 2 ans dans les archives de l'établissement. Lors d'un changement d'école de conduite de la part de
        l'élève, une copie peut être transmise à l'élève à sa demande. Les objectifs à atteindre durant la formation
        sont précisés dans les quatre compétences de formation du livret d'apprentissage remis à l'élève le jour de la
        signature du contrat. Lesdites compétences sont les suivantes :</p>
    <ul>
        <li>Maîtriser le maniement du véhicule dans un trafic faible ou nul ;</li>
        <li>Appréhender la route et circuler dans des conditions normales ;</li>
        <li>Circuler dans des conditions difficiles et partager la route avec les autres usagers ;</li>
        <li>Pratiquer une conduite autonome, sûre et économique</li>
    </ul>

    <h3>1) Formation théorique générale (code de la route)</h3>
    <h4>1.1. Programme de formation en vigueur :</h4>
    <p>La formation théorique générale dispensée par SMONI correspond au programme de l'épreuve théorique générale
        (ETG). Elle porte notamment sur la connaissance des règlements relatifs à la circulation et la conduite d'une
        voiture, ainsi que sur celle des bons comportements du conducteur. Seront également dispensés, les règles de
        sécurité routière à appliquer dans les tunnels, les précautions à prendre en quittant le véhicule, les facteurs
        de sécurité concernant le chargement de véhicule et les personnes transportées, les règles de conduite
        respectueuses de l'environnement, ainsi que la réglementation relative à l'obligation d'assurance et aux
        documents administratifs liés à l'utilisation du véhicule.</p>

    <h4>1.2. Déroulement de la formation :</h4>
    <p>L'enseignement théorique se déroulera :</p>
    <ul>
        <li>sur place et/ou à distance</li>
        <li>en cours collectif et/ou individuel</li>
    </ul>

    <h4>1.3. Moyens pédagogiques et techniques :</h4>
    <p>L'enseignement théorique se fera :</p>
    <p><strong>Si enseignement à distance :</strong></p>
    <ul>
        <li>Sur une plateforme en ligne (le lien du site et les identifiants d'accès seront fournis au paiement)</li>
        <li>Durée d'accès : 4 mois</li>
        <li>Éventuelle limitation de la fréquence d'utilisation</li>
        <li>Point de départ, et information de résultat ;</li>
    </ul>
    <p><strong>Si enseignement sur place :</strong></p>
    <ul>
        <li>Aux heures d'ouverture de SMONI, selon les disponibilités des moniteurs et sur rendez-vous</li>
        <li>Durée : en moyenne 1h avec un moniteur sinon en accès limité à 2h sans moniteur</li>
    </ul>

    <h4>1.4. Accompagnement à l'épreuve théorique générale (ETG) :</h4>
    <p>Lorsque l'élève est convoqué à l'épreuve théorique générale de l'examen du permis de conduire : il s'y rend par
        ses propres moyens.</p>
    <p><strong>NB :</strong> L'élève est dans l'obligation de se munir de la convocation à l'examen et d'une pièce
        d'identité valide, auquel cas il ne pourrait être admis à l'examen.</p>

    <h4>1.5. Epreuve théorique générale :</h4>
    <p>L'épreuve théorique générale est réglementée par l'Etat. L'organisation de cette épreuve est notamment assurée
        par des opérateurs privés agréés par l'Etat. Le paiement des frais s'effectuera soit :</p>
    <ul>
        <li>Directement par l'élève auprès de l'opérateur ;</li>
        <li>Par SMONI.</li>
    </ul>

    <h3>2) Formation pratique (Conduite)</h3>
    <h4>2.1. Calendrier et programme de formation :</h4>
    <p>Les rendez-vous s'organisent de commun accord avec l'élève par SMONI Auto-école et/ou exclusivement par l'élève
        sur notre site internet qui dès que possible mettra en ligne des agendas de nos enseignants selon leur
        disponibilité. Avec une fréquence maximale de 3h de cours par semaine dans la limite de 2h dans la journée ou
        dans le cas d'une conduite accélérée une possibilité de réserver autant de créneau que possible dans la limite
        de 2 heures successives par jour.</p>

    <h4>2.2. Déroulement de la formation :</h4>
    <p>L'enseignement pratique se déroule :</p>
    <ul>
        <li>Sur voies ouvertes à la circulation /Sur piste ;</li>
        <li>Sur simulateur avec/sans enseignant (selon la disponibilité) ;</li>
        <li>En cours individuel / collectif ;</li>
        <li>Sur boite manuelle / automatique</li>
    </ul>
    <p>Au titre du présent contrat, la durée d'une heure de conduite comprend le temps nécessaire notamment à l'accueil,
        la détermination de l'objectif, la leçon, l'évaluation et le bilan de la leçon. </p>
    <p>Le temps de conduite effective sera d'environ 45 minutes à condition que l'élève ait déjà lu et assimilé son
        cours de conduite avant ladite séance de conduite. Dans le cas contraire, et selon l'appréciation du moniteur la
        séance commencera par 15 min d'e-learning à bord du véhicule et se poursuivra par la conduite. Par conséquent,
        le temps de conduite effectif pourra donc être réduit. Pour éviter cela, l'élève est donc tenu de réviser ses
        cours de conduite théorique avant chaque séance de conduite.</p>

    <h4>2.3. Evaluation des compétences en fin de formation initiale :</h4>
    <p>Lors de la formation pratique prédéfinie lors de l'évaluation préalable, ou à tout moment à la demande de
        l'élève, l'enseignant se doit d'effectuer un bilan des compétences à l'apprenant afin de se situer dans la
        progression de l'élève.</p>
    <ul>
        <li>Lorsque l'élève satisfait à ce bilan, l'auto-école lui délivre une attestation de fin de formation initiale
            pour poursuite de la formation dans le cadre de la conduite supervisée ou de l'AAC. Dans le cadre d'une
            formation classique, l'élève sera directement inscrit sur la liste d'attente de demande de la date d'examen.
        </li>
        <li>Dans le cas contraire, selon le résultat obtenu par l'élève ainsi que son niveau, le moniteur se doit de
            préciser les points à approfondir. Lorsque le nombre d'heures prévues initialement au contrat, n'a pas suffi
            à l'apprenant pour lui permettre d'atteindre le niveau suffisant pour se présenter à l'épreuve pratique ou
            en cas d'échec à cette épreuve, un complément d'heures de formation pourra être proposé par SMONI.
            L'apprenant se réserve le droit d'accepter ou de refuser. En cas d'accord, un avenant au présent contrat
            sera signé entre toutes les parties.</li>
    </ul>

    <h4>2.4. Présentation à l'épreuve pratique du permis de conduire :</h4>
    <p>L'élève sera présenté à l'épreuve pratique par SMONI, suivant les dates arrêtées et communiquées par l'autorité
        administrative. En cas d'échec, et après accord entre les parties sur les besoins de l'élève, SMONI présentera
        ce dernier à une nouvelle épreuve pratique en fonction du calendrier qui lui sera communiqué par l'autorité
        administrative.</p>

    <h4>2.5. Accompagnement à l'épreuve pratique :</h4>
    <p>Le jour d'examen de l'épreuve pratique, SMONI se chargera d'accompagner l'apprenant sur le centre de l'examen.
        Aussi, elle se doit de mettre à la disposition de l'apprenant, le véhicule de l'école de conduite durant toute
        la période de l'épreuve. Les frais d'accompagnement facturés à ce titre par SMONI à l'élève correspondent à une
        heure de conduite, conformément aux dispositions de l'article R.213-3-3 du code de la route. De plus, l'élève
        devra régler le trajet aller-retour au centre d'examen. Ce trajet correspond à une ou deux heures de conduite
        selon la distance à parcourir jusqu'au centre d'examen.</p>

    <h4>2.6. Contrôle des élèves mineurs</h4>
    <p>L'établissement SMONI Auto-École s'engage à contrôler sa présence aux séances prévues dans le calendrier
        mentionné ci- dessus, et à avertir immédiatement le souscripteur en cas d'absence.</p>

    <h4>2.7. Rendez-vous pédagogique</h4>
    <p>L'établissement s'engage à organiser obligatoirement deux rendez-vous pédagogiques entre l'élève, le ou les
        accompagnateurs et l'enseignant. La présence d'au moins un accompagnateur est obligatoire. Ces rendez-vous ont
        pour finalité de mesurer la progression de l'élève ; à l'issue de la phase de conduite accompagnée, celui-ci
        devra avoir parcouru au minimum 3000 kilomètres et pendant au moins 1 an, et d'approfondir les connaissances de
        l'élève en matière de sécurité routière. Les rendez-vous pédagogiques d'une durée totale de six heures peuvent
        être organisés comme suit : soit deux séances de trois heures chacune, soit trois séances de deux heures
        chacune. Ils comportent une phase en circulation, d'une durée de deux heures, sur un véhicule appartenant à
        l'établissement, donnant lieu à une évaluation de la pratique de la conduite, un entretien, individuel
        concernant la conduite de l'élève avec l'accompagnateur. L'enseignant retrace les résultats des rendez-vous
        pédagogiques sur la fiche de suivi de formation et veille à ce que le livret d'apprentissage de l'élève soit
        correctement renseigné. L'élève est tenu de présenter son livret à l'établissement lors de chaque rendez-vous
        pédagogique, aux fins d'annotations. </p>
    <p>Les rendez-vous pédagogiques se déroulent de la manière suivante : </p>
    <ul>
        <li>Le premier, entre quatre et six mois après la fin de formation initiale. Cette période doit normalement
            correspondre à un parcours d'au moins mille kilomètres de conduite accompagnée, </li>
        <li>Le second, dans les deux mois avant la fin de la période de conduite accompagnée. Il doit intervenir lorsque
            trois mille kilomètres ou plus ont été parcourus.</li>
    </ul>
    <p>En cas de difficulté particulière, un rendez-vous pédagogique supplémentaire peut être organisé, soit à la
        demande de l'enseignant, soit à celle de l'élève ou de l'accompagnateur. </p>
    <p>Les rendez-vous pédagogiques sont des prestations hors forfait facturés selon le tarif en vigueur.</p>

    <h2>D. TARIF DES PRESTATIONS et PRIX DE LA FORMATION</h2>
    <p>Permis automobile</p>
    <img src="{{asset('images/image1.png')}}" alt="">
    <p>NB : formation passerelle 7H : 430€</p>
    <p>Permis moto</p>
    <img src="{{asset('images/image2.png')}}" alt="">
    <p>NB : formation passerelle 7H : 416€</p>

    <h2>E. LES OBLIGATIONS DES PARTIES</h2>
    <p>En cas d'annulation des leçons en formation pratique : sauf cas de force majeure ou motif légitime dûment
        justifié à SMONI, toute leçon non décommandée par l'élève au moins 48 heures à l'avance n'est pas remboursée ni
        reportée. Dans le cas où elle n'a pas été payée à l'avance, elle est considérée comme due. Toute leçon annulée
        par le moniteur devra être reportée.</p>

    <h3>1. Démarches administratives</h3>
    <p>En prenant en compte les termes de ce contrat, d'une part, l'élève peut choisir de mandater l'auto-école pour
        accomplir en son nom et place toutes les démarches et formalités nécessaires auprès de l'administration, en vue
        de l'enregistrement de son livret et de son dossier d'examen. L'apprenant est alors informé par l'auto-école, de
        la liste des documents à fournir pour constituer son dossier d'examen. L'apprenant garde la possibilité de
        mettre fin au mandat à tout moment conformément à la loi, moyennant, le cas échéant, le paiement d'une somme
        compensant strictement les moyens déboursés par l'auto-école jusqu'à la résiliation. D'autre part, l'auto-école,
        s'engage à déposer le dossier, dès lors qu'il est complet et à fournir à l'élève son numéro d'enregistrement
        préfectoral harmonisé (NEPH). Le mandataire ne saurait être tenu responsable du retard pris par le mandant pour
        finir les pièces justificatives ou de celui imputable à l'autorité compétente pour enregistrer ou valider la
        demande.</p>

    <h3>2. Inscription aux épreuves théorique et pratique du permis de conduire</h3>
    <p>L'inscription à l'épreuve théorique générale du code de la route ou à l'épreuve pratique du permis de conduire
        peut-être réalisée par l'apprenant ou par l'auto-école. Dans ce cas, en vertu du présent contrat, l'apprenant
        peut choisir de mandater l'auto-école pour accomplir en son nom et place toutes les démarches et formalités
        nécessaires auprès des organismes agréés pour l'épreuve théorique générale et de l'administration, en vue de la
        réservation des places d'examen. L'apprenant se réserve le droit, la possibilité de mettre un terme au mandat à
        tout moment conformément à la loi, moyennant, dans ce cas, le paiement d'une somme compensant strictement les
        moyens engagés par l'auto-école jusqu'à la résiliation. Quant à elle, l'auto-école, s'engage à inscrire l'élève
        aux épreuves théoriques ou pratiques du permis de conduire à une date en accord avec ce dernier.</p>
    <p>En cas de non-respect par l'élève des prescriptions pédagogiques de l'établissement ou du calendrier de
        formation, l'établissement se réserve la possibilité de surseoir à sa présentation aux épreuves du permis de
        conduire. Le responsable de l'établissement d'enseignement en informera les motivations à l'élève et ses parents
        et lui proposera un calendrier de formation complémentaire. L'élève pourra contester cette décision par écrit de
        façon motivée.</p>

    <h3>3. Obligations de l'élève</h3>
    <ul>
        <li>Être âgé de 16 ans minimum ou 15 ans minimum en cas d'apprentissage anticipé de la conduite ;</li>
        <li>Être détenteur, notamment lors des leçons pratiques, des documents suivants : livret d'apprentissage
            conforme à la réglementation, formulaire de la demande de permis de conduire validé par le préfet du lieu de
            département de son dépôt.</li>
        <li>Respecter le règlement intérieur de SMONI figurant en annexe du présent contrat et dont il a pris
            connaissance.</li>
    </ul>

    <h3>4. Obligations de SMONI</h3>
    <p>L'établissement a l'obligation de :</p>
    <ul>
        <li>Délivrer à l'élève aussi bien une formation théorique que pratique conforme aux programmes en vigueur ;</li>
        <li>Présenter le candidat à l'épreuve théorique en fournissant les moyens nécessaires sauf si le candidat
            souhaite se présenter directement.</li>
    </ul>

    <h2>F. PRÉSENTATION AUX EXAMENS</h2>
    <p>Si un élève décide de ne pas se présenter, il devra en avertir par écrit l'école de conduite au minimum une
        semaine à l'avance, sauf en cas de force majeure et /ou de motifs légitimes dûment constatés et justifiés.</p>

    <h2>G. LE OU LES ACCOMPAGNATEURS</h2>
    <p>Le ou les accompagnateur(s), cosignataires du présent contrat, s'engagent :</p>
    <ul>
        <li>À assurer un rôle actif et responsable d'accompagnateur,</li>
        <li>À être garant du comportement général de l'élève,</li>
        <li>À faciliter la formation de l'élève en fournissant tous les renseignements demandés dans les documents
            pédagogiques remis par l'établissement d'enseignement,</li>
        <li>À assister au rendez-vous préalable et aux rendez-vous pédagogiques.</li>
    </ul>
    <p>En cas de manquements graves à ses obligations (absence aux rendez-vous pédagogiques, condamnation au titre des
        infractions visées à l'article 2 de l'arrêté du 14/12/90), l'accompagnateur ne peut plus exercer ses fonctions
        et doit être remplacé.</p>

    <h2>H. LES MODALITÉS DE PAIEMENT</h2>
    <p>Le paiement de la formation se fera par les différents moyens ci-après :</p>
    <ul>
        <li>Carte bancaire ;</li>
        <li>Chèque ;</li>
        <li>Virement bancaire (rib en annexe)</li>
        <li>Espèces pour un maximum de 600€ par contrat ;</li>
        <li>Prélèvement SEPA.</li>
    </ul>
    <p>Le paiement pourra s'effectuer selon l'une des modalités suivantes :</p>
    <ul>
        <li>Avec une avance et le solde restant dû à une date donnée selon les termes négociés avec SMONI avant le
            passage des examens ;</li>
        <li>Paiement comptant en un seul versement par tous moyens dans la limite de 600€ par contrat si espèce ;</li>
        <li>A l'unité avant chaque prestation ;</li>
        <li>Échelonné en trois versements sans frais à des dates préalablement définies en total accord avec SMONI.</li>
    </ul>
    <p>Tout défaut de règlement des sommes dues dans un délai de 1 mois suivant leur échéance entraînera une mise en
        demeure des responsables légaux de l'élève. Toute mise en demeure restée sans effet, entraîne automatiquement
        une rupture de contrat sans aucune autre démarche de la part de notre établissement.</p>
    <p>L'établissement délivrera une facture à l'élève après le paiement de la prestation. Pour les prestations
        forfaitaires, la facture indiquera la liste détaillée des prestations comprises dans ledit forfait. Conformément
        à l'article 1 de l'arrêté du 3 octobre 1983, toute prestation dont le prix est égal ou supérieur à 25 € TTC fera
        l'objet de la délivrance d'une facture.</p>
    <p><strong>NB :</strong> Le non-respect des modalités de paiement préalablement définis entre les partis obligera
        l'élève à s'acquitter d'une pénalité calculée au prorata de la période de retard.</p>

    <h2>I. CONDITIONS DE RÉTRACTATIONS OU DE RÉSILIATION</h2>
    <h3>1. Rétractation</h3>
    <p>Dans le cadre d'un contrat conclu à distance tel que défini à l'article L. 221-1 du code de la consommation,
        l'apprenant bénéficie, à compter de la date de la signature du présent contrat, d'un droit de rétractation de 14
        jours conformément à l'article L. 221-18 du même code. Dans l'hypothèse où l'élève souhaite exercer ce droit, il
        adresse sa décision de se rétracter à SMONI soit par lettre recommandée ou envoi recommandé électronique avec
        avis de réception à l'adresse postale de SMONI ou par courriel à l'adresse électronique de SMONI. Si l'élève a
        expressément demandé à débuter sa formation avant l'expiration du délai de rétractation. Cela implique son
        consentement donc son acceptation définitive du présent contrat. Ainsi dit, la période de rétractation devient
        caduque et aucun remboursement ne peut découler de cette situation. Dans le cas contraire c'est-à-dire si
        l'élève dénonce le contrat dans la période de rétractation sans démarrer son apprentissage, SMONI s'engage à
        procéder au remboursement après lui avoir facturé les frais des prestations réalisées jusqu'à la notification
        par l'élève de sa décision de se rétracter. En cas de prestations déjà réglées par l'élève, dans le cadre d'un
        forfait, le remboursement s'effectuera dans les 21 jours suivant la réception de la lettre recommandée de
        l'élève, tout en procédant à la déduction des prestations déjà réalisées. En cas de prestations non encore
        facturées à l'élève, dans le cadre d'un forfait, l'élève devra régler toutes les prestations déjà réalisées
        jusqu'à la réception de sa lettre de résiliation.</p>
    <p><strong>NB : Tout démarrage de la formation provoque des frais de résiliation.</strong></p>

    <h3>2. Résiliation</h3>
    <p>L'élève peut résilier le présent contrat à tout moment par lettre recommandée ou envoi recommandé électronique
        avec avis de réception à l'adresse postale de SMONI ou par courriel à l'adresse électronique de l'école de
        conduite, moyennant paiement des prestations déjà réalisées. La résiliation prend effet 15 jours à compter de la
        date de la première présentation de la lettre recommandée ou de l'envoi recommandé électronique. Ce délai de
        préavis ne s'applique pas en cas de force majeur. L'établissement se réserve le droit de résilier le présent
        contrat en cas de violence avérée, de mise en danger d'autrui, d'incivilités ou de manquements répétés à l'une
        de ses obligations issues du présent contrat (Par exemple : Des retards de paiement non régularisés). La
        résiliation du présent contrat avant son terme entraîne l'apurement définitif des comptes et des frais de
        résiliation. L'établissement facturera le montant des prestations réalisées jusqu'à la date de la prise d'effet
        de la résiliation en plus des dits frais de résiliation. En cas de prestations déjà réglées, par l'élève dans le
        cadre d'un forfait, le remboursement s'effectue au prorata des prestations déjà réalisées. En cas de prestations
        non encore facturées à l'élève dans le cadre d'un forfait, la facturation s'effectue au prorata des prestations
        déjà réalisées. Le dossier de l'élève lui sera restitué à sa demande ou à celle d'un tiers dûment mandaté par
        lui. De plus, tout contrat entamé pendant la période de rétractation, rend caduc la période de rétractation et
        ne peut faire l'objet d'aucun remboursement. Toute heure non consommée après la réussite de l'élève aux
        différents examens ne pourra faire l'objet d'une résiliation du contrat, ni d'un quelconque remboursement. En
        effet, sa réussite entraînera la fin anticipée du contrat car les objectifs auront été atteints.</p>
    <p><strong>NB : Toute demande de résiliation après la période de rétractation provoque des frais de
            résiliation.</strong></p>

    <h2>J. SOUSCRIPTION PAR L'ÉTABLISSEMENT À UN DISPOSITIF DE GARANTIE FINANCIÈRE</h2>
    <p>En cas de défaillance de SMONI, celle-ci a souscrit à un dispositif de garantie financière :</p>
    <p>Nom et adresse de l'organisme garant :</p>
    <p>N° du contrat :</p>
    <p>Date de Validité :</p>
    <p>Montant garanti :</p>
    <p>L'établissement est titulaire d'un contrat d'assurance de responsabilité civile garantissant ses véhicules et
        couvrant les dommages pouvant être causés aux tiers ainsi qu'aux personnes se trouvant à l'intérieur des
        véhicules pendant la formation ou lors des examens pratiques dans les conditions prévues à l'article L. 211-1 du
        code des assurances, souscrit auprès de : ________________________ sous le numéro de police
        ________________________</p>

    <h2>K. RÈGLEMENT DES LITIGES</h2>
    <p>En cas de désaccord ou litige entre les parties, le présent contrat est soumis au droit français.</p>
    <p>Tout litige découlant de la validité, exécution, réalisation du présent contrat est soumis aux tribunaux
        compétents dans les conditions de droit commun.</p>
    <p>A défaut de solution amiable, l'élève peut recourir gratuitement, dans les conditions prévues aux articles L.
        612-1 et suivants et R. 612-1 et suivants du code de la consommation à un médiateur de la consommation en vue de
        la résolution amiable de tout litige l'opposant à SMONI, relatif au présent contrat (nom et coordonnées du
        médiateur) : ________________________________________________________. Avant de saisir le médiateur, l'apprenant
        doit avoir adressé au préalable une réclamation écrite à l'école de conduite. Il doit saisir le médiateur dans
        le délai d'un an maximum à compter de sa réclamation écrite.</p>

    <h2>L. RGPD : PROTECTION DE VOS DONNÉES PERSONNELLES</h2>
    <p>Dans un souci de transparence, nous vous informons que vous avez :</p>
    <p><strong>Le droit d'accès :</strong> Vous pouvez demander directement au responsable d'un fichier s'il détient des
        informations sur vous, et demander à ce que l'on vous communique l'intégralité de ces données.</p>
    <p><strong>Le droit d’opposition :</strong> : Vous pouvez vous opposer, pour des motifs légitimes, à figurer dans un
        fichier. Vous pouvez également vous opposer à ce que les données vous concernant soient diffusées, transmises ou
        conservées. </p>
    <p><strong>Le droit de rectification :</strong> Vous pouvez demander la rectification des informations inexactes
        vous concernant. Le droit de rectification complète le droit d’accès. </p>

    <p><strong>Le droit au déréférencement :</strong> Vous pouvez saisir les moteurs de recherche de demandes de
        déréférencement d’une
        page web associée à vos nom et prénom. </p>

    <p><strong>Le droit à la portabilité :</strong> Vous pouvez récupérer une partie de vos données dans un format
        lisible par une machine. Libre à vous ensuite de stocker ailleurs ces données ou de les transférer d’un service
        à un autre.</p>
    <p><strong>Le droit à l’effacement :</strong> Vous pouvez demander à un organisme l’effacement de données
        personnelles vous
        concernant.</p>
    <p><strong>Le droit à la limitation :</strong> Vous pouvez demander à un organisme de « geler » temporairement
        l’utilisation de
        certaines de vos données : il ne pourra alors plus s’en servir pendant un certain délai.</p>

    <p>
        L’élève est informé que les données personnelles recueillies sur ce contrat font l’objet de traitements
        automatisés nécessaires à l’exécution de ce dernier. L'établissement est responsable du traitement de ces
        données personnelles qu’elle collecte et traite pour établir le contrat et fournir les services d’enseignement à
        la conduite qui y sont mentionnés. Seules les données personnelles strictement nécessaires à l’exécution du
        présent contrat sont traitées par SMONI. Elles sont obligatoires : à défaut la fourniture des services
        d’apprentissage à la conduite pourrait être suspendue. Elles ne font l’objet d’aucun transfert ni de
        communication à des tiers sauf obligations législatives ou réglementaires. Dans le cas où l’élève a mandaté
        SMONI pour effectuer les formalités nécessaires à l’inscription à l’épreuve théorique générale (code) où à
        l’examen de la conduite, ainsi qu’à l’établissement de son permis de conduire, SMONI transmettra aux opérateurs
        responsables les données personnelles strictement nécessaires à l’exécution de ces formalités. Dans le cas où
        SMONI fait appel à des sous-traitants comme les éditeurs de solutions pédagogiques pour proposer des supports
        aux formateurs comme aux élèves ou encore mettre à disposition des applications permettant de gérer
        l’inscription, la planification et le suivi d’un élève à une formation de conduite ; ils agissent au nom et pour
        le compte de SMONI. L'établissement s’engage à conclure avec ses sous-traitants un contrat de traitement de
        données personnelles conforme à l’article 28 du règlement n° 2016/679, dit règlement général sur la protection
        des données (RGPD).
        L'établissement s’engage à mettre en œuvre les mesures techniques et organisationnelles appropriées afin de
        garantir un niveau de sécurité optimal des données personnelles qu’il traite. Les données recueillies seront
        conservées pendant toute la durée du contrat et seront supprimées au bout de 5 ans à compter de son terme.
        L’élève bénéficie d’un droit d’accès, de portabilité, de rectification, d’effacement de ses données
        personnelles, ainsi qu’un droit de limitation ou d’opposition au traitement de celles-ci. Il peut exercer ses
        droits en s’adressant directement à SMONI par l’adresse mail suivante : contact@smoni.fr en tant que
        consommateur.
        Si l’élève ne souhaite pas faire l’objet de prospection commerciale par voie téléphonique, il est informé de son
        droit de s’inscrire gratuitement sur la liste d’opposition au démarchage téléphonique Bloctel sur le site
        internet : https://www.bloctel.gouv.fr/ ou par courrier à la société Opposetel – Service Bloctel, 6 rue
        Nicolas-Siret – 10000 Troyes.

    </p>

    <h2>A- Règlement intérieur</h2>
    <p>SMONI a adopté un règlement intérieur annexé au présent contrat. Ce dit règlement intérieur est porté à la
        connaissance de l’élève avant la conclusion du contrat.</p>

    <p>L’acceptation de ce contrat implique l’acceptation de l’élève à ce que ses données soient recueillies et traitées
        par nos bases de données numériques. SMONI ne vend pas ses données numériques mais travaille avec des
        partenaires qui pourront vous proposer leurs services avec votre autorisation.</p>

    <div class="checkbox-group">
        <p><input type="checkbox"> L’élève souhaite que ses données soient utilisées par les partenaires de SMONI à des
            fins de prospections.</p>
        <p><input type="checkbox"> L’élève souhaite que ses données soient utilisées par SMONI à des fins de
            prospections.</p>
        <p><input type="checkbox"> L’élève souhaite commencer sa formation avant la fin de la période de rétractation.
        </p>
        <p><input type="checkbox"> L’élève mandate SMONI à effectuer pour son compte ses démarches administratives.</p>
        <ul>
            <li><input type="checkbox"> La demande de son numéro N.E.P.H</li>
            <li><input type="checkbox"> L’inscription à l’épreuve théorique du permis</li>
            <li><input type="checkbox"> L’inscription à l’épreuve pratique du permis</li>
        </ul>
        <p><input type="checkbox"> L’élève fera lui/elle-même ses démarches administratives concernant</p>
        <ul>
            <li><input type="checkbox"> La demande de son numéro N.E.P.H</li>
            <li><input type="checkbox"> L’inscription à l’épreuve théorique du permis</li>
            <li><input type="checkbox"> L’inscription à l’épreuve pratique du permis</li>
        </ul>
    </div>

    <p style="text-align: right;">Fait à Vincennes, Le ___________________ en deux exemplaires originaux.</p>

    <table style="width: 100%;">
        <td style="width: 30%;">
            <p><b>Signature de l’élève</b></p>
            <p>Précédée de la mention</p>
            <p>« Lu et approuvé »</p>
        </td>
        <td style="width: 30%;">
            <p><b>Signature du représentant légal de l’élève mineur,</b> </p>
            <p>Précédée de la mention (Le cas échéant)</p>
            <p>« Lu et approuvé »</p>
        </td>
        <td style="width: 30%;">
            <p> <b>Signature du responsable de SMONI et cachet</b></p>
            <p>Précédée de la mention</p>
            <p>« Lu et approuvé »</p>
        </td>
    </table>


</body>

</html>