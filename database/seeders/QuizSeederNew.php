<?php

namespace Database\Seeders;

use App\Models\QuizCategory;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizSeederNew extends Seeder
{
    public function run(): void
    {
        // Create or get categories
        $vi = QuizCategory::firstOrCreate(
            ['code' => 'VI'],
            [
                'name' => 'Vérifications Intérieures',
                'description' => 'Questions sur les vérifications intérieures du véhicule',
                'pass_score' => 7, // 70% of 10 questions
            ]
        );

        $ve = QuizCategory::firstOrCreate(
            ['code' => 'VE'],
            [
                'name' => 'Vérifications Extérieures',
                'description' => 'Questions sur les vérifications extérieures du véhicule',
                'pass_score' => 7,
            ]
        );

        $qser = QuizCategory::firstOrCreate(
            ['code' => 'QSER'],
            [
                'name' => 'Questions de Sécurité Routière',
                'description' => 'Questions sur la sécurité routière et les équipements',
                'pass_score' => 7,
            ]
        );

        $ps = QuizCategory::firstOrCreate(
            ['code' => 'PS'],
            [
                'name' => 'Premiers Secours',
                'description' => 'Questions sur les premiers secours',
                'pass_score' => 7,
            ]
        );

        // Delete existing questions to avoid duplicates
        QuizQuestion::whereIn('category_id', [$vi->id, $ve->id, $qser->id, $ps->id])->delete();

        // VI Questions (Interior Checks) - From odd-numbered rows
        $viQuestions = [
            ['question_number' => 1, 'question_text' => 'Montrez la commande de réglage de hauteur des feux.', 'correct_answer' => 'Dispositif situé en général à gauche du volant.'],
            ['question_number' => 3, 'question_text' => 'Montrez où s\'effectue le remplissage du produit lave-glace.', 'correct_answer' => 'Sous le capot, réservoir avec un pictogramme de pare-brise.'],
            ['question_number' => 5, 'question_text' => 'Faites fonctionner les essuie-glaces avants du véhicule.', 'correct_answer' => 'Commande au volant, généralement à droite.'],
            ['question_number' => 11, 'question_text' => 'Montrez l\'indicateur de niveau de carburant.', 'correct_answer' => 'Jauge sur le tableau de bord avec pictogramme de pompe.'],
            ['question_number' => 13, 'question_text' => 'Actionnez le dégivrage de la lunette arrière et montrez le voyant ou le repère correspondant.', 'correct_answer' => 'Bouton avec pictogramme de lunette arrière avec flèches.'],
            ['question_number' => 19, 'question_text' => 'Montrez la commande de réglage du volant.', 'correct_answer' => 'Levier ou bouton sous le volant.'],
            ['question_number' => 21, 'question_text' => 'Positionnez la commande pour diriger l\'air vers le pare-brise.', 'correct_answer' => 'Commande de ventilation avec pictogramme de pare-brise.'],
            ['question_number' => 33, 'question_text' => 'Montrez la commande permettant d\'actionner le régulateur de vitesse.', 'correct_answer' => 'Commande au volant ou sur le tableau de bord.'],
            ['question_number' => 37, 'question_text' => 'Montrez la commande permettant de désactiver l\'airbag du passager avant.', 'correct_answer' => 'Interrupteur dans la boîte à gants ou sur le côté du tableau de bord.'],
            ['question_number' => 39, 'question_text' => 'Montrez le voyant signalant l\'absence de bouclage de la ceinture de sécurité du conducteur.', 'correct_answer' => 'Voyant rouge avec pictogramme de ceinture sur le tableau de bord.'],
            ['question_number' => 41, 'question_text' => 'Vérifiez la présence de l\'attestation d\'assurance du véhicule et de sa vignette sur le pare-brise.', 'correct_answer' => 'Vignette verte sur le pare-brise et attestation dans le véhicule.'],
            ['question_number' => 43, 'question_text' => 'Allumez le(s) feu(x) de brouillard arrière(s) et montrez le voyant correspondant.', 'correct_answer' => 'Commande d\'éclairage, voyant orange avec pictogramme de feu brouillard.'],
            ['question_number' => 45, 'question_text' => 'Montrez comment régler la hauteur de l\'appui-tête du siège conducteur.', 'correct_answer' => 'Bouton sur le côté de l\'appui-tête à presser pour ajuster.'],
            ['question_number' => 47, 'question_text' => 'De quelle couleur est le voyant qui indique au conducteur que le feu de brouillard arrière est allumé ?', 'correct_answer' => 'Orange.'],
            ['question_number' => 49, 'question_text' => 'Montrez la commande de recyclage de l\'air.', 'correct_answer' => 'Bouton avec pictogramme de voiture et flèche circulaire.'],
            ['question_number' => 51, 'question_text' => 'Allumez les feux de route et montrez le voyant correspondant.', 'correct_answer' => 'Commande d\'éclairage, voyant bleu avec pictogramme de phare.'],
        ];

        foreach ($viQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $vi->id]));
        }

        // VE Questions (Exterior Checks) - From even-numbered rows
        $veQuestions = [
            ['question_number' => 4, 'question_text' => 'Contrôlez l\'état du flanc sur l\'un des pneumatiques.', 'correct_answer' => 'Vérifier l\'absence de coupures, déchirures, hernies sur le flanc du pneu.'],
            ['question_number' => 7, 'question_text' => 'Vérifiez l\'état et la propreté des plaques d\'immatriculation.', 'correct_answer' => 'Les plaques doivent être propres, lisibles et non détériorées.'],
            ['question_number' => 14, 'question_text' => 'Contrôlez l\'état, la propreté et le fonctionnement de tous les clignotants côté trottoir.', 'correct_answer' => 'Les clignotants doivent être propres, non cassés et fonctionner correctement.'],
            ['question_number' => 16, 'question_text' => 'Contrôlez l\'état, la propreté et le fonctionnement du ou des feux de brouillard arrière.', 'correct_answer' => 'Le feu doit être propre, non cassé et s\'allumer correctement.'],
            ['question_number' => 18, 'question_text' => 'Contrôlez l\'état, la propreté et le fonctionnement des feux de détresse à l\'avant et à l\'arrière.', 'correct_answer' => 'Tous les clignotants doivent fonctionner simultanément.'],
            ['question_number' => 26, 'question_text' => 'Contrôlez l\'état, la propreté et le fonctionnement des feux de croisement.', 'correct_answer' => 'Les feux doivent être propres, non cassés et s\'allumer correctement.'],
            ['question_number' => 28, 'question_text' => 'Vérifiez l\'état et la propreté des dispositifs réfléchissants.', 'correct_answer' => 'Les catadioptres doivent être propres et non cassés.'],
            ['question_number' => 30, 'question_text' => 'Contrôlez l\'état, la propreté et le fonctionnement des feux de position à l\'avant et à l\'arrière du véhicule.', 'correct_answer' => 'Les feux doivent être propres, non cassés et s\'allumer correctement.'],
            ['question_number' => 40, 'question_text' => 'Vérifiez le fonctionnement de l\'éclairage de la plaque d\'immatriculation à l\'arrière.', 'correct_answer' => 'La plaque doit être éclairée quand les feux de position sont allumés.'],
            ['question_number' => 44, 'question_text' => 'Avec l\'assistance de l\'accompagnateur, contrôlez l\'état, la propreté et le fonctionnement du ou des feux de recul.', 'correct_answer' => 'Les feux blancs doivent s\'allumer en marche arrière.'],
            ['question_number' => 52, 'question_text' => 'Vérifiez l\'état, la propreté et le fonctionnement des feux diurnes.', 'correct_answer' => 'Les feux diurnes s\'allument automatiquement au démarrage.'],
            ['question_number' => 54, 'question_text' => 'Vérifiez la présence du triangle de pré-signalisation.', 'correct_answer' => 'Triangle rouge réfléchissant dans le coffre.'],
            ['question_number' => 56, 'question_text' => 'Montrez où s\'effectue le changement d\'une ampoule à l\'avant du véhicule.', 'correct_answer' => 'Accès par le capot moteur, derrière le bloc optique.'],
            ['question_number' => 58, 'question_text' => 'Montrez où s\'effectue le changement d\'une ampoule à l\'arrière du véhicule.', 'correct_answer' => 'Accès par le coffre ou l\'extérieur selon le modèle.'],
            ['question_number' => 60, 'question_text' => 'Ouvrez et refermez le coffre, puis vérifiez sa bonne fermeture.', 'correct_answer' => 'Le coffre doit se fermer complètement et le voyant doit s\'éteindre.'],
            ['question_number' => 62, 'question_text' => 'Ouvrez et refermez le capot, puis vérifiez sa bonne fermeture.', 'correct_answer' => 'Le capot doit être bien verrouillé et ne pas bouger.'],
            ['question_number' => 64, 'question_text' => 'Montrez où se situent les gicleurs de lave-glace avant.', 'correct_answer' => 'Sur le capot ou sur les balais d\'essuie-glace.'],
            ['question_number' => 66, 'question_text' => 'Contrôlez l\'état du flanc sur l\'un des pneumatiques.', 'correct_answer' => 'Vérifier l\'absence de coupures, déchirures, hernies.'],
            ['question_number' => 68, 'question_text' => 'Montrez le voyant indiquant une baisse de pression d\'air d\'un pneumatique.', 'correct_answer' => 'Voyant orange avec pictogramme de pneu et point d\'exclamation.'],
        ];

        foreach ($veQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $ve->id]));
        }

        // QSER Questions (Safety and Road Rules)
        $qserQuestions = [
            ['question_number' => 1, 'question_text' => 'Pourquoi doit-on régler la hauteur des feux ?', 'correct_answer' => 'Pour ne pas éblouir les autres usagers.'],
            ['question_number' => 2, 'question_text' => 'Pourquoi est-il préférable d\'utiliser un liquide spécial en hiver ?', 'correct_answer' => 'Pour éviter le gel du liquide.'],
            ['question_number' => 3, 'question_text' => 'Comment détecter l\'usure des essuie-glaces en circulation ?', 'correct_answer' => 'En cas de pluie, lorsqu\'ils laissent des traces sur le pare-brise.'],
            ['question_number' => 4, 'question_text' => 'Citez un endroit où l\'on peut trouver les pressions préconisées pour les pneumatiques ?', 'correct_answer' => 'Sur une plaque sur une portière, dans la notice d\'utilisation, ou au niveau de la trappe à carburant.'],
            ['question_number' => 7, 'question_text' => 'Quelles sont les précautions à prendre en cas d\'installation d\'un porte vélo ?', 'correct_answer' => 'La plaque d\'immatriculation et les feux doivent être visibles.'],
            ['question_number' => 8, 'question_text' => 'Quelle est la conséquence d\'un niveau insuffisant du liquide de frein ?', 'correct_answer' => 'Une perte d\'efficacité du freinage.'],
            ['question_number' => 11, 'question_text' => 'Quelles sont les précautions à prendre lors du remplissage du réservoir ?', 'correct_answer' => 'Arrêter le moteur, ne pas fumer, ne pas téléphoner.'],
            ['question_number' => 13, 'question_text' => 'Quelle peut être la conséquence d\'une panne de dégivrage de la lunette arrière ?', 'correct_answer' => 'Une insuffisance ou une absence de visibilité vers l\'arrière.'],
            ['question_number' => 14, 'question_text' => 'Quelle est la signification d\'un clignotement plus rapide ?', 'correct_answer' => 'Non fonctionnement de l\'une des ampoules.'],
            ['question_number' => 16, 'question_text' => 'Dans quels cas utilise-t-on les feux de brouillard arrière ?', 'correct_answer' => 'Par temps de brouillard et neige.'],
            ['question_number' => 18, 'question_text' => 'Dans quels cas doit-on utiliser les feux de détresse ?', 'correct_answer' => 'En cas de panne, d\'accident ou de ralentissement important.'],
            ['question_number' => 19, 'question_text' => 'Pourquoi est-il important de bien régler son volant ?', 'correct_answer' => 'Le confort de conduite, l\'accessibilité aux commandes, la visibilité du tableau de bord, l\'efficacité des airbags.'],
            ['question_number' => 21, 'question_text' => 'Citez deux éléments complémentaires permettant un désembuage efficace.', 'correct_answer' => 'La commande de vitesse de ventilation, la commande d\'air chaud, la climatisation.'],
            ['question_number' => 22, 'question_text' => 'Quel est le principal risque d\'un manque d\'huile moteur ?', 'correct_answer' => 'Un risque de détérioration ou de casse du moteur.'],
            ['question_number' => 26, 'question_text' => 'Quelles sont les conséquences d\'un mauvais réglage des feux de croisement ?', 'correct_answer' => 'Une mauvaise vision vers l\'avant et un risque d\'éblouissement des autres usagers.'],
            ['question_number' => 28, 'question_text' => 'Quelle est l\'utilité des dispositifs réfléchissants ?', 'correct_answer' => 'Rendre visible le véhicule la nuit.'],
            ['question_number' => 30, 'question_text' => 'Par temps clair, à quelle distance les feux de position doivent-ils être visibles ?', 'correct_answer' => 'À 150 mètres.'],
            ['question_number' => 33, 'question_text' => 'Sans actionner la commande du régulateur, comment le désactiver rapidement ?', 'correct_answer' => 'En appuyant sur la pédale de frein ou d\'embrayage.'],
            ['question_number' => 35, 'question_text' => 'Quel est le risque d\'un manque d\'huile moteur ?', 'correct_answer' => 'Un risque de détérioration ou de casse du moteur.'],
            ['question_number' => 37, 'question_text' => 'Dans quelle situation doit-on désactiver l\'airbag passager ?', 'correct_answer' => 'Lors du transport d\'un enfant à l\'avant dans un siège auto, dos à la route.'],
            ['question_number' => 39, 'question_text' => 'En règle générale, à partir de quel âge un enfant peut-il être installé sur le siège passager avant du véhicule ?', 'correct_answer' => '10 ans.'],
            ['question_number' => 40, 'question_text' => 'Un défaut d\'éclairage de la plaque lors du contrôle technique entraîne-t-il une contre-visite ?', 'correct_answer' => 'Oui.'],
            ['question_number' => 41, 'question_text' => 'Quels sont les deux autres documents obligatoires à présenter en cas de contrôle par les forces de l\'ordre ?', 'correct_answer' => 'Le certificat d\'immatriculation et le permis de conduire.'],
            ['question_number' => 43, 'question_text' => 'Pouvez-vous utiliser les feux de brouillard arrière par forte pluie ?', 'correct_answer' => 'Non.'],
            ['question_number' => 44, 'question_text' => 'Quelles sont les deux utilités des feux de recul ?', 'correct_answer' => 'Éclairer la zone de recul la nuit et avertir les autres usagers de la manœuvre.'],
            ['question_number' => 45, 'question_text' => 'Quelle est l\'utilité de l\'appui-tête ?', 'correct_answer' => 'Permet de retenir le mouvement de la tête en cas de choc et de limiter les blessures.'],
            ['question_number' => 47, 'question_text' => 'Quelle est la différence entre un voyant orange et un voyant rouge ?', 'correct_answer' => 'Rouge : Une anomalie de fonctionnement ou un danger. Orange : un élément important.'],
            ['question_number' => 49, 'question_text' => 'Quel peut être le risque de maintenir le recyclage de l\'air de manière prolongée ?', 'correct_answer' => 'Un risque de mauvaise visibilité par l\'apparition de buée sur les surfaces vitrées.'],
            ['question_number' => 51, 'question_text' => 'Quel est le risque de maintenir les feux de route lors d\'un croisement avec d\'autres usagers ?', 'correct_answer' => 'Un risque d\'éblouissement des autres usagers.'],
            ['question_number' => 52, 'question_text' => 'Quelle est l\'utilité des feux diurnes ?', 'correct_answer' => 'Rendre le véhicule plus visible le jour.'],
            ['question_number' => 54, 'question_text' => 'Utilise-t-on le triangle de pré-signalisation sur autoroute ?', 'correct_answer' => 'Non.'],
            ['question_number' => 56, 'question_text' => 'Quelles sont les conséquences en cas de panne d\'un feu de croisement ?', 'correct_answer' => 'Une mauvaise visibilité et le risque d\'être confondu avec un deux roues.'],
            ['question_number' => 58, 'question_text' => 'Quelles sont les conséquences en cas de panne d\'un feu de position arrière ?', 'correct_answer' => 'Être mal vu et un risque de collision.'],
            ['question_number' => 60, 'question_text' => 'Quels sont les risques de circuler avec des objets sur la plage arrière ?', 'correct_answer' => 'Une mauvaise visibilité vers l\'arrière et un risque de projection en cas de freinage brusque ou de choc.'],
            ['question_number' => 62, 'question_text' => 'En roulant, quel est le risque d\'une mauvaise fermeture du capot ?', 'correct_answer' => 'Un risque d\'ouverture du capot pouvant entraîner un accident.'],
            ['question_number' => 64, 'question_text' => 'Quelle est la principale conséquence d\'un dispositif de lave-glace défaillant ?', 'correct_answer' => 'Une mauvaise visibilité due à l\'impossibilité de nettoyer le pare-brise.'],
            ['question_number' => 66, 'question_text' => 'Qu\'est-ce que l\'aquaplanage, et quelle peut être sa conséquence ?', 'correct_answer' => 'La présence d\'un film d\'eau entre le pneumatique et la chaussée pouvant entraîner une perte de contrôle du véhicule.'],
            ['question_number' => 68, 'question_text' => 'À quelle fréquence est-il préconisé de vérifier la pression d\'air des pneumatiques ?', 'correct_answer' => 'Tous les mois.'],
        ];

        foreach ($qserQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $qser->id]));
        }

        // PS Questions (First Aid)
        $psQuestions = [
            ['question_number' => 1, 'question_text' => 'Comment et pourquoi protéger une zone de danger en cas d\'accident de la route ?', 'correct_answer' => 'En délimitant clairement et largement la zone de danger de façon visible pour protéger les victimes et éviter un sur-accident.'],
            ['question_number' => 2, 'question_text' => 'Quels comportements adopter en cas de diffusion du signal d\'alerte du Système d\'Alerte et d\'Information des Populations (SAIP) ?', 'correct_answer' => 'Se mettre en sécurité, s\'informer grâce aux médias et sites internet des autorités dès que leur consultation est possible, respecter les consignes des autorités.'],
            ['question_number' => 6, 'question_text' => 'Qu\'est-ce qu\'une perte de connaissance ?', 'correct_answer' => 'C\'est lorsque la victime ne répond pas et ne réagit pas mais respire.'],
            ['question_number' => 10, 'question_text' => 'Hors autoroute ou endroit dangereux, en cas de panne ou d\'accident, où doit être placé le triangle de pré-signalisation ?', 'correct_answer' => 'Le triangle de pré-signalisation doit être placé à une distance d\'environ 30 m de la panne ou de l\'accident, ou avant un virage, ou un sommet de côte.'],
            ['question_number' => 12, 'question_text' => 'Quels sont les risques pour une personne en perte de connaissance qui est allongée sur le dos ?', 'correct_answer' => 'L\'arrêt respiratoire et l\'arrêt cardiaque.'],
            ['question_number' => 15, 'question_text' => 'Pourquoi l\'alerte auprès des services de secours doit-elle être rapide et précise ?', 'correct_answer' => 'Pour permettre aux services de secours d\'apporter les moyens adaptés aux victimes dans le délai le plus court.'],
            ['question_number' => 17, 'question_text' => 'Dans quel cas peut-on positionner une victime en Position Latérale de Sécurité (PLS) ?', 'correct_answer' => 'Si la victime ne répond pas, ne réagit pas et respire.'],
            ['question_number' => 20, 'question_text' => 'Quel est l\'objectif du Signal d\'Alerte et d\'Information des Populations (SAIP) ?', 'correct_answer' => 'Avertir la population d\'un danger imminent ou qu\'un événement grave est en train de se produire.'],
            ['question_number' => 23, 'question_text' => 'Comment est diffusée l\'alerte émise par le Signal d\'Alerte et d\'Information aux Populations (SAIP) ?', 'correct_answer' => 'Grâce aux sirènes, aux médias tels que Radio France et France Télévision ou encore grâce à l\'application SAIP.'],
            ['question_number' => 24, 'question_text' => 'Quelle est la solution en cas de panne de batterie pour démarrer le véhicule sans le déplacer ?', 'correct_answer' => 'Brancher une deuxième batterie en parallèle ou la remplacer.'],
            ['question_number' => 25, 'question_text' => 'Quel est le risque de circuler avec un frein de parking mal desserré ?', 'correct_answer' => 'Une dégradation du système de freinage.'],
            ['question_number' => 27, 'question_text' => 'Quelle est la conséquence d\'une température trop élevée du liquide de refroidissement ?', 'correct_answer' => 'Une surchauffe ou une casse moteur.'],
            ['question_number' => 29, 'question_text' => 'Quels sont les risques pour une personne en perte de connaissance qui est allongée sur le dos ?', 'correct_answer' => 'L\'arrêt respiratoire et l\'arrêt cardiaque.'],
            ['question_number' => 31, 'question_text' => 'Quand utilise-t-on les feux de détresse ?', 'correct_answer' => 'En cas de panne, d\'accident ou de ralentissement important.'],
            ['question_number' => 32, 'question_text' => 'Si un dégagement d\'urgence de la victime est nécessaire, où doit-elle être déplacée ?', 'correct_answer' => 'Dans un endroit suffisamment éloigné du danger et de ses conséquences.'],
            ['question_number' => 34, 'question_text' => 'Quelles sont les précautions à prendre lors du remplissage du réservoir ?', 'correct_answer' => 'Arrêter le moteur, ne pas fumer, ne pas téléphoner.'],
            ['question_number' => 36, 'question_text' => 'Dans quel cas peut-on utiliser l\'avertisseur sonore en agglomération ?', 'correct_answer' => 'En cas de danger immédiat.'],
            ['question_number' => 38, 'question_text' => 'À quelle fréquence est-il préconisé de vérifier la pression des pneus ?', 'correct_answer' => 'Chaque mois, pour une utilisation normale de son véhicule, et avant chaque long trajet.'],
            ['question_number' => 42, 'question_text' => 'Quels sont les risques pour une personne victime d\'une hémorragie ?', 'correct_answer' => 'Entraîner pour la victime une détresse circulatoire ou un arrêt cardiaque.'],
            ['question_number' => 46, 'question_text' => 'Quelle est la conséquence en cas de panne des feux de stop ?', 'correct_answer' => 'Un manque d\'information pour les usagers suiveurs et un risque de collision.'],
        ];

        foreach ($psQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $ps->id]));
        }
    }
}
