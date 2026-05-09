<?php

namespace Database\Seeders;

use App\Models\QuizCategory;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get categories
        $ve = QuizCategory::firstOrCreate(
            ['code' => 'VE'],
            [
                'name' => 'Vérifications Extérieures',
                'description' => 'Questions sur les vérifications extérieures du véhicule',
                'pass_score' => 15,
            ]
        );

        $qser = QuizCategory::firstOrCreate(
            ['code' => 'QSER'],
            [
                'name' => 'Questions de Sécurité Routière',
                'description' => 'Questions sur la sécurité routière et les équipements',
                'pass_score' => 15,
            ]
        );

        $ps = QuizCategory::firstOrCreate(
            ['code' => 'PS'],
            [
                'name' => 'Premiers Secours',
                'description' => 'Questions sur les premiers secours et la sécurité routière',
                'pass_score' => 15,
            ]
        );

        // Delete existing questions to avoid duplicates
        QuizQuestion::whereIn('category_id', [$ve->id, $qser->id, $ps->id])->delete();
        // VE Questions (External Checks)
        $veQuestions = [
            [
                'question_number' => 4,
                'practical_action' => 'Contrôlez l\'état du flanc sur l\'un des pneumatiques.',
                'theoretical_question' => 'Citez un endroit où l\'on peut trouver les pressions préconisées pour les pneumatiques ?',
                'correct_answer' => 'Elles sont indiquées soit sur une plaque sur une portière, soit dans la notice d\'utilisation du véhicule, soit au niveau de la trappe à carburant.',
            ],
            [
                'question_number' => 7,
                'practical_action' => 'Vérifiez l\'état et la propreté des plaques d\'immatriculation.',
                'theoretical_question' => 'Quelles sont les précautions à prendre en cas d\'installation d\'un porte vélo ?',
                'correct_answer' => 'La plaque d\'immatriculation et les feux doivent être visibles.',
            ],
            [
                'question_number' => 14,
                'practical_action' => 'Contrôlez l\'état, la propreté et le fonctionnement de tous les clignotants côté trottoir.',
                'theoretical_question' => 'Quelle est la signification d\'un clignotement plus rapide ?',
                'correct_answer' => 'Non fonctionnement de l\'une des ampoules.',
            ],
            [
                'question_number' => 16,
                'practical_action' => 'Contrôlez l\'état, la propreté et le fonctionnement du ou des feux de brouillard arrière.',
                'theoretical_question' => 'Dans quels cas les utilise-t-on ?',
                'correct_answer' => 'Par temps de brouillard et neige.',
            ],
            [
                'question_number' => 18,
                'practical_action' => 'Contrôlez l\'état, la propreté et le fonctionnement des feux de détresse à l\'avant et à l\'arrière.',
                'theoretical_question' => 'Dans quels cas doit-on les utiliser ?',
                'correct_answer' => 'En cas de panne, d\'accident ou de ralentissement important.',
            ],
            [
                'question_number' => 26,
                'practical_action' => 'Contrôlez l\'état, la propreté et le fonctionnement des feux de croisement.',
                'theoretical_question' => 'Quelles sont les conséquences d\'un mauvais réglage de ces feux ?',
                'correct_answer' => 'Une mauvaise vision vers l\'avant et un risque d\'éblouissement des autres usagers.',
            ],
            [
                'question_number' => 28,
                'practical_action' => 'Vérifiez l\'état et la propreté des dispositifs réfléchissants.',
                'theoretical_question' => 'Quelle est l\'utilité des dispositifs réfléchissants ?',
                'correct_answer' => 'Rendre visible le véhicule la nuit.',
            ],
            [
                'question_number' => 30,
                'practical_action' => 'Contrôlez l\'état, la propreté et le fonctionnement des feux de position à l\'avant et à l\'arrière du véhicule.',
                'theoretical_question' => 'Par temps clair, à quelle distance doivent-ils être visibles ?',
                'correct_answer' => 'À 150 mètres.',
            ],
            [
                'question_number' => 40,
                'practical_action' => 'Vérifiez le fonctionnement de l\'éclairage de la plaque d\'immatriculation à l\'arrière.',
                'theoretical_question' => 'Un défaut d\'éclairage de la plaque lors du contrôle technique entraîne-t-il une contre-visite ?',
                'correct_answer' => 'Oui.',
            ],
            [
                'question_number' => 44,
                'practical_action' => 'Avec l\'assistance de l\'accompagnateur, contrôlez l\'état, la propreté et le fonctionnement du ou des feux de recul.',
                'theoretical_question' => 'Quelles sont leurs deux utilités ?',
                'correct_answer' => 'Éclairer la zone de recul la nuit et avertir les autres usagers de la manœuvre.',
            ],
            [
                'question_number' => 52,
                'practical_action' => 'Vérifiez l\'état, la propreté et le fonctionnement des feux diurnes.',
                'theoretical_question' => 'Quelle est leur utilité ?',
                'correct_answer' => 'Rendre le véhicule plus visible le jour.',
            ],
            [
                'question_number' => 54,
                'practical_action' => 'Vérifiez la présence du triangle de pré-signalisation.',
                'theoretical_question' => 'Utilise-t-on le triangle de pré-signalisation sur autoroute ?',
                'correct_answer' => 'Non.',
            ],
            [
                'question_number' => 56,
                'practical_action' => 'Montrez où s\'effectue le changement d\'une ampoule à l\'avant du véhicule.',
                'theoretical_question' => 'Quelles sont les conséquences en cas de panne d\'un feu de croisement ?',
                'correct_answer' => 'Une mauvaise visibilité et le risque d\'être confondu avec un deux roues.',
            ],
            [
                'question_number' => 58,
                'practical_action' => 'Montrez où s\'effectue le changement d\'une ampoule à l\'arrière du véhicule.',
                'theoretical_question' => 'Quelles sont les conséquences en cas de panne d\'un feu de position arrière ?',
                'correct_answer' => 'Être mal vu et un risque de collision.',
            ],
            [
                'question_number' => 60,
                'practical_action' => 'Ouvrez et refermez le coffre, puis vérifiez sa bonne fermeture.',
                'theoretical_question' => 'Quels sont les risques de circuler avec des objets sur la plage arrière ?',
                'correct_answer' => 'Une mauvaise visibilité vers l\'arrière et un risque de projection en cas de freinage brusque ou de choc.',
            ],
            [
                'question_number' => 62,
                'practical_action' => 'Ouvrez et refermez le capot, puis vérifiez sa bonne fermeture.',
                'theoretical_question' => 'En roulant, quel est le risque d\'une mauvaise fermeture du capot ?',
                'correct_answer' => 'Un risque d\'ouverture du capot pouvant entraîner un accident.',
            ],
            [
                'question_number' => 64,
                'practical_action' => 'Montrez où se situent les gicleurs de lave-glace avant.',
                'theoretical_question' => 'Quelle est la principale conséquence d\'un dispositif de lave-glace défaillant ?',
                'correct_answer' => 'Une mauvaise visibilité due à l\'impossibilité de nettoyer le pare-brise.',
            ],
            [
                'question_number' => 66,
                'practical_action' => 'Contrôlez l\'état du flanc sur l\'un des pneumatiques.',
                'theoretical_question' => 'Qu\'est-ce que l\'aquaplanage, et quelle peut être sa conséquence ?',
                'correct_answer' => 'La présence d\'un film d\'eau entre le pneumatique et la chaussée pouvant entraîner une perte de contrôle du véhicule.',
            ],
            [
                'question_number' => 68,
                'practical_action' => 'Montrez le voyant indiquant une baisse de pression d\'air d\'un pneumatique.',
                'theoretical_question' => 'À quelle fréquence est-il préconisé de vérifier la pression d\'air des pneumatiques ?',
                'correct_answer' => 'Tous les mois.',
            ],
        ];

        foreach ($veQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $ve->id]));
        }

        // QSER Questions (Safety and Road Rules)
        $qserQuestions = [
            [
                'question_number' => 1,
                'practical_action' => 'Montrez la commande de réglage de hauteur des feux.',
                'theoretical_question' => 'Pourquoi doit-on régler la hauteur des feux ?',
                'correct_answer' => 'Pour ne pas éblouir les autres usagers.',
            ],
            [
                'question_number' => 3,
                'practical_action' => 'Montrez où s\'effectue le remplissage du produit lave-glace.',
                'theoretical_question' => 'Pourquoi est-il préférable d\'utiliser un liquide spécial en hiver ?',
                'correct_answer' => 'Pour éviter le gel du liquide.',
            ],
            [
                'question_number' => 5,
                'practical_action' => 'Faites fonctionner les essuie-glaces avants du véhicule.',
                'theoretical_question' => 'Comment détecter leur usure en circulation ?',
                'correct_answer' => 'En cas de pluie, lorsqu\'ils laissent des traces sur le pare-brise.',
            ],
            [
                'question_number' => 8,
                'practical_action' => 'Montrez où s\'effectue le contrôle du niveau du liquide de frein.',
                'theoretical_question' => 'Quelle est la conséquence d\'un niveau insuffisant du liquide de frein ?',
                'correct_answer' => 'Une perte d\'efficacité du freinage.',
            ],
            [
                'question_number' => 11,
                'practical_action' => 'Montrez l\'indicateur de niveau de carburant.',
                'theoretical_question' => 'Quelles sont les précautions à prendre lors du remplissage du réservoir ?',
                'correct_answer' => 'Arrêter le moteur, ne pas fumer, ne pas téléphoner.',
            ],
            [
                'question_number' => 13,
                'practical_action' => 'Actionnez le dégivrage de la lunette arrière et montrez le voyant ou le repère correspondant.',
                'theoretical_question' => 'Quelle peut être la conséquence d\'une panne de dégivrage de la lunette arrière ?',
                'correct_answer' => 'Une insuffisance ou une absence de visibilité vers l\'arrière.',
            ],
            [
                'question_number' => 19,
                'practical_action' => 'Montrez la commande de réglage du volant.',
                'theoretical_question' => 'Pourquoi est-il important de bien régler son volant ?',
                'correct_answer' => 'Le confort de conduite, l\'accessibilité aux commandes, la visibilité du tableau de bord, l\'efficacité des airbags.',
            ],
            [
                'question_number' => 21,
                'practical_action' => 'Positionnez la commande pour diriger l\'air vers le pare-brise.',
                'theoretical_question' => 'Citez deux éléments complémentaires permettant un désembuage efficace.',
                'correct_answer' => 'La commande de vitesse de ventilation, la commande d\'air chaud, la climatisation.',
            ],
            [
                'question_number' => 22,
                'practical_action' => 'Montrez où doit s\'effectuer le contrôle du niveau d\'huile moteur.',
                'theoretical_question' => 'Quel est le principal risque d\'un manque d\'huile moteur ?',
                'correct_answer' => 'Un risque de détérioration ou de casse du moteur.',
            ],
            [
                'question_number' => 33,
                'practical_action' => 'Montrez la commande permettant d\'actionner le régulateur de vitesse.',
                'theoretical_question' => 'Sans actionner la commande du régulateur, comment le désactiver rapidement ?',
                'correct_answer' => 'En appuyant sur la pédale de frein ou d\'embrayage.',
            ],
            [
                'question_number' => 35,
                'practical_action' => 'Montrez où s\'effectue le remplissage de l\'huile moteur.',
                'theoretical_question' => 'Quel est le risque d\'un manque d\'huile moteur ?',
                'correct_answer' => 'Un risque de détérioration ou de casse du moteur.',
            ],
            [
                'question_number' => 37,
                'practical_action' => 'Montrez la commande permettant de désactiver l\'airbag du passager avant.',
                'theoretical_question' => 'Dans quelle situation doit-on le désactiver ?',
                'correct_answer' => 'Lors du transport d\'un enfant à l\'avant dans un siège auto, dos à la route.',
            ],
            [
                'question_number' => 39,
                'practical_action' => 'Montrez le voyant signalant l\'absence de bouclage de la ceinture de sécurité du conducteur.',
                'theoretical_question' => 'En règle générale, à partir de quel âge un enfant peut-il être installé sur le siège passager avant du véhicule ?',
                'correct_answer' => '10 ans.',
            ],
            [
                'question_number' => 41,
                'practical_action' => 'Vérifiez la présence de l\'attestation d\'assurance du véhicule et de sa vignette sur le pare-brise.',
                'theoretical_question' => 'Quels sont les deux autres documents obligatoires à présenter en cas de contrôle par les forces de l\'ordre ?',
                'correct_answer' => 'Le certificat d\'immatriculation et le permis de conduire.',
            ],
            [
                'question_number' => 43,
                'practical_action' => 'Allumez le(s) feu(x) de brouillard arrière(s) et montrez le voyant correspondant.',
                'theoretical_question' => 'Pouvez-vous les utiliser par forte pluie ?',
                'correct_answer' => 'Non.',
            ],
            [
                'question_number' => 45,
                'practical_action' => 'Montrez comment régler la hauteur de l\'appui-tête du siège conducteur.',
                'theoretical_question' => 'Quelle est son utilité ?',
                'correct_answer' => 'Permet de retenir le mouvement de la tête en cas de choc et de limiter les blessures.',
            ],
            [
                'question_number' => 47,
                'practical_action' => 'De quelle couleur est le voyant qui indique au conducteur que le feu de brouillard arrière est allumé ?',
                'theoretical_question' => 'Quelle est la différence entre un voyant orange et un voyant rouge ?',
                'correct_answer' => 'Rouge : Une anomalie de fonctionnement ou un danger. Orange : un élément important.',
            ],
            [
                'question_number' => 49,
                'practical_action' => 'Montrez la commande de recyclage de l\'air.',
                'theoretical_question' => 'Quel peut être le risque de maintenir le recyclage de l\'air de manière prolongée ?',
                'correct_answer' => 'Un risque de mauvaise visibilité par l\'apparition de buée sur les surfaces vitrées.',
            ],
            [
                'question_number' => 51,
                'practical_action' => 'Allumez les feux de route et montrez le voyant correspondant.',
                'theoretical_question' => 'Quel est le risque de maintenir les feux de route lors d\'un croisement avec d\'autres usagers ?',
                'correct_answer' => 'Un risque d\'éblouissement des autres usagers.',
            ],
        ];

        foreach ($qserQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $qser->id]));
        }

        // PS Questions (First Aid)
        $psQuestions = [
            [
                'question_number' => 2,
                'practical_action' => 'Comment et pourquoi protéger une zone de danger en cas d\'accident de la route ?',
                'theoretical_question' => 'Quels comportements adopter en cas de diffusion du signal d\'alerte du Système d\'Alerte et d\'Information des Populations (SAIP) ?',
                'correct_answer' => 'Se mettre en sécurité, s\'informer grâce aux médias et sites internet des autorités dès que leur consultation est possible, respecter les consignes des autorités.',
            ],
            [
                'question_number' => 6,
                'practical_action' => 'Comment vérifier la respiration d\'une victime ?',
                'theoretical_question' => 'Qu\'est-ce qu\'une perte de connaissance ?',
                'correct_answer' => 'C\'est lorsque la victime ne répond pas et ne réagit pas mais respire.',
            ],
            [
                'question_number' => 10,
                'practical_action' => 'Pourquoi faut-il pratiquer immédiatement une réanimation cardio-pulmonaire sur une victime en arrêt cardiaque ?',
                'theoretical_question' => 'Hors autoroute ou endroit dangereux, en cas de panne ou d\'accident, où doit être placé le triangle de pré-signalisation ?',
                'correct_answer' => 'Le triangle de pré-signalisation doit être placé à une distance d\'environ 30 m de la panne ou de l\'accident, ou avant un virage, ou un sommet de côte.',
            ],
            [
                'question_number' => 12,
                'practical_action' => 'Comment arrêter une hémorragie ?',
                'theoretical_question' => 'Quels sont les risques pour une personne en perte de connaissance qui est allongée sur le dos ?',
                'correct_answer' => 'L\'arrêt respiratoire et l\'arrêt cardiaque.',
            ],
            [
                'question_number' => 15,
                'practical_action' => 'Quelles sont les trois informations à transmettre aux services de secours ?',
                'theoretical_question' => 'Pourquoi l\'alerte auprès des services de secours doit-elle être rapide et précise ?',
                'correct_answer' => 'Pour permettre aux services de secours d\'apporter les moyens adaptés aux victimes dans le délai le plus court.',
            ],
            [
                'question_number' => 17,
                'practical_action' => 'Quel comportement doit-on adopter en présence d\'une victime en arrêt cardiaque ?',
                'theoretical_question' => 'Dans quel cas peut-on positionner une victime en Position Latérale de Sécurité (PLS) ?',
                'correct_answer' => 'Si la victime ne répond pas, ne réagit pas et respire.',
            ],
            [
                'question_number' => 20,
                'practical_action' => 'En cas de panne ou d\'accident, quel équipement de sécurité doit être porté avant de quitter le véhicule ?',
                'theoretical_question' => 'Quel est l\'objectif du Signal d\'Alerte et d\'Information des Populations (SAIP) ?',
                'correct_answer' => 'Avertir la population d\'un danger imminent ou qu\'un événement grave est en train de se produire.',
            ],
            [
                'question_number' => 23,
                'practical_action' => 'À partir de quel âge peut-on suivre une formation aux premiers secours ?',
                'theoretical_question' => 'Comment est diffusée l\'alerte émise par le Signal d\'Alerte et d\'Information aux Populations (SAIP) ?',
                'correct_answer' => 'Grâce aux sirènes, aux médias tels que Radio France et France Télévision ou encore grâce à l\'application SAIP.',
            ],
            [
                'question_number' => 29,
                'practical_action' => 'Citez les trois manières d\'évaluer l\'état de conscience d\'une victime.',
                'theoretical_question' => 'Quels sont les risques pour une personne en perte de connaissance qui est allongée sur le dos ?',
                'correct_answer' => 'L\'arrêt respiratoire et l\'arrêt cardiaque.',
            ],
            [
                'question_number' => 42,
                'practical_action' => 'Qu\'est-ce qu\'une hémorragie ?',
                'theoretical_question' => 'Quels sont les risques pour une personne victime d\'une hémorragie ?',
                'correct_answer' => 'Entraîner pour la victime une détresse circulatoire ou un arrêt cardiaque.',
            ],
            [
                'question_number' => 24,
                'practical_action' => 'Montrez l\'emplacement de la batterie du véhicule.',
                'theoretical_question' => 'Quelle est la solution en cas de panne de batterie pour démarrer le véhicule sans le déplacer ?',
                'correct_answer' => 'Brancher une deuxième batterie en parallèle ou la remplacer.',
            ],
            [
                'question_number' => 25,
                'practical_action' => 'De quelle couleur est le voyant qui indique une défaillance du système de freinage ?',
                'theoretical_question' => 'Quel est le risque de circuler avec un frein de parking mal desserré ?',
                'correct_answer' => 'Une dégradation du système de freinage.',
            ],
            [
                'question_number' => 27,
                'practical_action' => 'Montrez le voyant d\'alerte signalant une température trop élevée du liquide de refroidissement.',
                'theoretical_question' => 'Quelle est la conséquence d\'une température trop élevée de ce liquide ?',
                'correct_answer' => 'Une surchauffe ou une casse moteur.',
            ],
            [
                'question_number' => 31,
                'practical_action' => 'Actionnez les feux de détresse.',
                'theoretical_question' => 'Quand les utilise-t-on ?',
                'correct_answer' => 'En cas de panne, d\'accident ou de ralentissement important.',
            ],
            [
                'question_number' => 32,
                'practical_action' => 'Sur le flanc d\'un pneumatique, désignez le repère du témoin d\'usure de la bande de roulement.',
                'theoretical_question' => 'Si un dégagement d\'urgence de la victime est nécessaire, où doit-elle être déplacée ?',
                'correct_answer' => 'Dans un endroit suffisamment éloigné du danger et de ses conséquences.',
            ],
            [
                'question_number' => 34,
                'practical_action' => 'Ouvrez la trappe à carburant et/ou vérifiez la bonne fermeture du bouchon.',
                'theoretical_question' => 'Quelles sont les précautions à prendre lors du remplissage du réservoir ?',
                'correct_answer' => 'Arrêter le moteur, ne pas fumer, ne pas téléphoner.',
            ],
            [
                'question_number' => 36,
                'practical_action' => 'Sans l\'actionner, montrez la commande de l\'avertisseur sonore.',
                'theoretical_question' => 'Dans quel cas peut-on utiliser l\'avertisseur sonore en agglomération ?',
                'correct_answer' => 'En cas de danger immédiat.',
            ],
            [
                'question_number' => 38,
                'practical_action' => 'À l\'aide de la plaque indicative, donnez la pression préconisée pour les pneumatiques arrières, véhicule chargé.',
                'theoretical_question' => 'À quelle fréquence est-il préconisé de vérifier la pression des pneus ?',
                'correct_answer' => 'Chaque mois, pour une utilisation normale de son véhicule, et avant chaque long trajet.',
            ],
            [
                'question_number' => 46,
                'practical_action' => 'Avec l\'assistance de l\'accompagnateur, contrôlez l\'état, la propreté et le fonctionnement des feux de stop.',
                'theoretical_question' => 'Quelle est la conséquence en cas de panne des feux de stop ?',
                'correct_answer' => 'Un manque d\'information pour les usagers suiveurs et un risque de collision.',
            ],
        ];

        foreach ($psQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $ps->id]));
        }
    }
}
