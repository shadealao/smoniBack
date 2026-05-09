<?php

namespace Database\Seeders;

use App\Models\QuizCategory;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizSeederMultipleChoice extends Seeder
{
    public function run(): void
    {
        // Create or get categories
        $vi = QuizCategory::firstOrCreate(
            ['code' => 'VI'],
            [
                'name' => 'Vérifications Intérieures',
                'description' => 'Questions sur les vérifications intérieures du véhicule',
                'pass_score' => 7,
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

        // Delete existing questions
        QuizQuestion::whereIn('category_id', [$vi->id, $ve->id, $qser->id, $ps->id])->delete();

        // VI Questions with Multiple Choice
        $viQuestions = [
            [
                'question_number' => 1,
                'question_text' => 'Où se trouve généralement la commande de réglage de hauteur des feux ?',
                'options' => ['À gauche du volant', 'À droite du volant', 'Sur le tableau de bord central', 'Près du levier de vitesse'],
                'correct_option_index' => 0,
                'correct_answer' => 'À gauche du volant'
            ],
            [
                'question_number' => 3,
                'question_text' => 'Où s\'effectue le remplissage du produit lave-glace ?',
                'options' => ['Sous le capot, réservoir avec pictogramme de pare-brise', 'Dans le coffre', 'Près du réservoir de carburant', 'À côté du moteur'],
                'correct_option_index' => 0,
                'correct_answer' => 'Sous le capot, réservoir avec pictogramme de pare-brise'
            ],
            [
                'question_number' => 5,
                'question_text' => 'Où se trouve la commande des essuie-glaces ?',
                'options' => ['Au volant, généralement à droite', 'Au volant, généralement à gauche', 'Sur le tableau de bord', 'Près du levier de vitesse'],
                'correct_option_index' => 0,
                'correct_answer' => 'Au volant, généralement à droite'
            ],
            [
                'question_number' => 11,
                'question_text' => 'Où se trouve l\'indicateur de niveau de carburant ?',
                'options' => ['Sur le tableau de bord avec pictogramme de pompe', 'Sous le capot', 'Dans le coffre', 'Sur la portière'],
                'correct_option_index' => 0,
                'correct_answer' => 'Sur le tableau de bord avec pictogramme de pompe'
            ],
            [
                'question_number' => 13,
                'question_text' => 'Comment reconnaît-on le bouton de dégivrage de la lunette arrière ?',
                'options' => ['Pictogramme de lunette arrière avec flèches', 'Pictogramme de pare-brise', 'Pictogramme de ventilateur', 'Pictogramme de climatisation'],
                'correct_option_index' => 0,
                'correct_answer' => 'Pictogramme de lunette arrière avec flèches'
            ],
            [
                'question_number' => 19,
                'question_text' => 'Où se trouve la commande de réglage du volant ?',
                'options' => ['Levier ou bouton sous le volant', 'Sur le tableau de bord', 'Sur la portière', 'Près du siège'],
                'correct_option_index' => 0,
                'correct_answer' => 'Levier ou bouton sous le volant'
            ],
            [
                'question_number' => 21,
                'question_text' => 'Comment diriger l\'air vers le pare-brise ?',
                'options' => ['Commande de ventilation avec pictogramme de pare-brise', 'Ouvrir les fenêtres', 'Activer la climatisation', 'Augmenter le chauffage'],
                'correct_option_index' => 0,
                'correct_answer' => 'Commande de ventilation avec pictogramme de pare-brise'
            ],
            [
                'question_number' => 33,
                'question_text' => 'Où se trouve généralement la commande du régulateur de vitesse ?',
                'options' => ['Au volant ou sur le tableau de bord', 'Près du levier de vitesse', 'Sur la portière', 'Sous le siège'],
                'correct_option_index' => 0,
                'correct_answer' => 'Au volant ou sur le tableau de bord'
            ],
            [
                'question_number' => 37,
                'question_text' => 'Où se trouve la commande de désactivation de l\'airbag passager ?',
                'options' => ['Dans la boîte à gants ou sur le côté du tableau de bord', 'Sur le volant', 'Sous le siège', 'Dans le coffre'],
                'correct_option_index' => 0,
                'correct_answer' => 'Dans la boîte à gants ou sur le côté du tableau de bord'
            ],
            [
                'question_number' => 39,
                'question_text' => 'De quelle couleur est le voyant de ceinture de sécurité non bouclée ?',
                'options' => ['Rouge', 'Orange', 'Vert', 'Bleu'],
                'correct_option_index' => 0,
                'correct_answer' => 'Rouge'
            ],
            [
                'question_number' => 41,
                'question_text' => 'Où doit être placée la vignette d\'assurance ?',
                'options' => ['Sur le pare-brise', 'Dans la boîte à gants', 'Sur la lunette arrière', 'Dans le coffre'],
                'correct_option_index' => 0,
                'correct_answer' => 'Sur le pare-brise'
            ],
            [
                'question_number' => 43,
                'question_text' => 'De quelle couleur est le voyant du feu de brouillard arrière ?',
                'options' => ['Orange', 'Rouge', 'Vert', 'Bleu'],
                'correct_option_index' => 0,
                'correct_answer' => 'Orange'
            ],
            [
                'question_number' => 45,
                'question_text' => 'Comment régler la hauteur de l\'appui-tête ?',
                'options' => ['Bouton sur le côté de l\'appui-tête à presser', 'Tourner l\'appui-tête', 'Tirer vers le haut', 'Utiliser un levier sous le siège'],
                'correct_option_index' => 0,
                'correct_answer' => 'Bouton sur le côté de l\'appui-tête à presser'
            ],
            [
                'question_number' => 47,
                'question_text' => 'De quelle couleur est le voyant du feu de brouillard arrière allumé ?',
                'options' => ['Orange', 'Rouge', 'Vert', 'Bleu'],
                'correct_option_index' => 0,
                'correct_answer' => 'Orange'
            ],
            [
                'question_number' => 49,
                'question_text' => 'Quel pictogramme représente le recyclage de l\'air ?',
                'options' => ['Voiture avec flèche circulaire', 'Ventilateur', 'Flocon de neige', 'Thermomètre'],
                'correct_option_index' => 0,
                'correct_answer' => 'Voiture avec flèche circulaire'
            ],
            [
                'question_number' => 51,
                'question_text' => 'De quelle couleur est le voyant des feux de route ?',
                'options' => ['Bleu', 'Vert', 'Orange', 'Rouge'],
                'correct_option_index' => 0,
                'correct_answer' => 'Bleu'
            ],
        ];

        foreach ($viQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $vi->id]));
        }

        // VE Questions with Multiple Choice
        $veQuestions = [
            [
                'question_number' => 4,
                'question_text' => 'Que doit-on vérifier sur le flanc d\'un pneumatique ?',
                'options' => ['L\'absence de coupures, déchirures et hernies', 'La couleur du pneu', 'La marque du pneu', 'Le numéro de série'],
                'correct_option_index' => 0,
                'correct_answer' => 'L\'absence de coupures, déchirures et hernies'
            ],
            [
                'question_number' => 7,
                'question_text' => 'Dans quel état doivent être les plaques d\'immatriculation ?',
                'options' => ['Propres, lisibles et non détériorées', 'Juste visibles', 'Peu importe l\'état', 'Seulement propres'],
                'correct_option_index' => 0,
                'correct_answer' => 'Propres, lisibles et non détériorées'
            ],
            [
                'question_number' => 14,
                'question_text' => 'Que doit-on vérifier sur les clignotants ?',
                'options' => ['Propreté, état et fonctionnement', 'Seulement le fonctionnement', 'Seulement la propreté', 'Seulement l\'état'],
                'correct_option_index' => 0,
                'correct_answer' => 'Propreté, état et fonctionnement'
            ],
            [
                'question_number' => 16,
                'question_text' => 'Comment doit être le feu de brouillard arrière ?',
                'options' => ['Propre, non cassé et fonctionnel', 'Juste fonctionnel', 'Juste propre', 'Peu importe'],
                'correct_option_index' => 0,
                'correct_answer' => 'Propre, non cassé et fonctionnel'
            ],
            [
                'question_number' => 18,
                'question_text' => 'Comment fonctionnent les feux de détresse ?',
                'options' => ['Tous les clignotants fonctionnent simultanément', 'Seulement les feux avant', 'Seulement les feux arrière', 'Alternativement'],
                'correct_option_index' => 0,
                'correct_answer' => 'Tous les clignotants fonctionnent simultanément'
            ],
            [
                'question_number' => 26,
                'question_text' => 'Dans quel état doivent être les feux de croisement ?',
                'options' => ['Propres, non cassés et fonctionnels', 'Juste fonctionnels', 'Juste propres', 'Peu importe'],
                'correct_option_index' => 0,
                'correct_answer' => 'Propres, non cassés et fonctionnels'
            ],
            [
                'question_number' => 28,
                'question_text' => 'Dans quel état doivent être les catadioptres ?',
                'options' => ['Propres et non cassés', 'Juste visibles', 'Peu importe', 'Seulement propres'],
                'correct_option_index' => 0,
                'correct_answer' => 'Propres et non cassés'
            ],
            [
                'question_number' => 30,
                'question_text' => 'Comment doivent être les feux de position ?',
                'options' => ['Propres, non cassés et fonctionnels', 'Juste fonctionnels', 'Juste propres', 'Peu importe'],
                'correct_option_index' => 0,
                'correct_answer' => 'Propres, non cassés et fonctionnels'
            ],
            [
                'question_number' => 40,
                'question_text' => 'Quand la plaque d\'immatriculation doit-elle être éclairée ?',
                'options' => ['Quand les feux de position sont allumés', 'Toujours', 'Jamais', 'Seulement la nuit'],
                'correct_option_index' => 0,
                'correct_answer' => 'Quand les feux de position sont allumés'
            ],
            [
                'question_number' => 44,
                'question_text' => 'De quelle couleur sont les feux de recul ?',
                'options' => ['Blancs', 'Rouges', 'Orange', 'Jaunes'],
                'correct_option_index' => 0,
                'correct_answer' => 'Blancs'
            ],
            [
                'question_number' => 52,
                'question_text' => 'Quand s\'allument les feux diurnes ?',
                'options' => ['Automatiquement au démarrage', 'Manuellement', 'Seulement la nuit', 'Jamais'],
                'correct_option_index' => 0,
                'correct_answer' => 'Automatiquement au démarrage'
            ],
            [
                'question_number' => 54,
                'question_text' => 'Où doit se trouver le triangle de pré-signalisation ?',
                'options' => ['Dans le coffre', 'Sous le capot', 'Dans l\'habitacle', 'Dans la boîte à gants'],
                'correct_option_index' => 0,
                'correct_answer' => 'Dans le coffre'
            ],
            [
                'question_number' => 56,
                'question_text' => 'Où s\'effectue le changement d\'ampoule à l\'avant ?',
                'options' => ['Par le capot moteur, derrière le bloc optique', 'Par l\'extérieur', 'Par le coffre', 'Par l\'habitacle'],
                'correct_option_index' => 0,
                'correct_answer' => 'Par le capot moteur, derrière le bloc optique'
            ],
            [
                'question_number' => 58,
                'question_text' => 'Où s\'effectue le changement d\'ampoule à l\'arrière ?',
                'options' => ['Par le coffre ou l\'extérieur selon le modèle', 'Toujours par le coffre', 'Toujours par l\'extérieur', 'Par le capot'],
                'correct_option_index' => 0,
                'correct_answer' => 'Par le coffre ou l\'extérieur selon le modèle'
            ],
            [
                'question_number' => 60,
                'question_text' => 'Comment vérifier la bonne fermeture du coffre ?',
                'options' => ['Le coffre doit se fermer complètement et le voyant s\'éteindre', 'Juste fermer le coffre', 'Vérifier visuellement', 'Tirer dessus'],
                'correct_option_index' => 0,
                'correct_answer' => 'Le coffre doit se fermer complètement et le voyant s\'éteindre'
            ],
            [
                'question_number' => 62,
                'question_text' => 'Comment vérifier la bonne fermeture du capot ?',
                'options' => ['Le capot doit être bien verrouillé et ne pas bouger', 'Juste fermer le capot', 'Vérifier visuellement', 'Appuyer dessus'],
                'correct_option_index' => 0,
                'correct_answer' => 'Le capot doit être bien verrouillé et ne pas bouger'
            ],
            [
                'question_number' => 64,
                'question_text' => 'Où se situent les gicleurs de lave-glace avant ?',
                'options' => ['Sur le capot ou sur les balais d\'essuie-glace', 'Dans le pare-brise', 'Sous le capot', 'Dans le coffre'],
                'correct_option_index' => 0,
                'correct_answer' => 'Sur le capot ou sur les balais d\'essuie-glace'
            ],
            [
                'question_number' => 66,
                'question_text' => 'Que doit-on vérifier sur le flanc du pneumatique ?',
                'options' => ['L\'absence de coupures, déchirures et hernies', 'La pression', 'La marque', 'La couleur'],
                'correct_option_index' => 0,
                'correct_answer' => 'L\'absence de coupures, déchirures et hernies'
            ],
            [
                'question_number' => 68,
                'question_text' => 'Quel pictogramme indique une baisse de pression des pneus ?',
                'options' => ['Pneu avec point d\'exclamation', 'Pneu seul', 'Roue', 'Clé'],
                'correct_option_index' => 0,
                'correct_answer' => 'Pneu avec point d\'exclamation'
            ],
        ];

        foreach ($veQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $ve->id]));
        }

        // QSER Questions (continuing in next part due to length)
        $qserQuestions = [
            [
                'question_number' => 1,
                'question_text' => 'Pourquoi doit-on régler la hauteur des feux ?',
                'options' => ['Pour ne pas éblouir les autres usagers', 'Pour mieux voir', 'Pour économiser l\'énergie', 'Pour le style'],
                'correct_option_index' => 0,
                'correct_answer' => 'Pour ne pas éblouir les autres usagers'
            ],
            [
                'question_number' => 2,
                'question_text' => 'Pourquoi utiliser un liquide spécial lave-glace en hiver ?',
                'options' => ['Pour éviter le gel du liquide', 'Pour mieux nettoyer', 'Pour sentir bon', 'Pour économiser'],
                'correct_option_index' => 0,
                'correct_answer' => 'Pour éviter le gel du liquide'
            ],
            [
                'question_number' => 3,
                'question_text' => 'Comment détecter l\'usure des essuie-glaces ?',
                'options' => ['Ils laissent des traces sur le pare-brise', 'Ils font du bruit', 'Ils sont décolorés', 'Ils sont rigides'],
                'correct_option_index' => 0,
                'correct_answer' => 'Ils laissent des traces sur le pare-brise'
            ],
            [
                'question_number' => 4,
                'question_text' => 'Où trouve-t-on les pressions préconisées pour les pneus ?',
                'options' => ['Sur une portière, dans la notice ou trappe à carburant', 'Sur les pneus', 'Sous le capot', 'Dans le coffre'],
                'correct_option_index' => 0,
                'correct_answer' => 'Sur une portière, dans la notice ou trappe à carburant'
            ],
            [
                'question_number' => 7,
                'question_text' => 'Que faut-il vérifier avec un porte-vélo ?',
                'options' => ['Plaque et feux doivent être visibles', 'Seulement la plaque', 'Seulement les feux', 'Rien de spécial'],
                'correct_option_index' => 0,
                'correct_answer' => 'Plaque et feux doivent être visibles'
            ],
            [
                'question_number' => 8,
                'question_text' => 'Quelle est la conséquence d\'un niveau insuffisant de liquide de frein ?',
                'options' => ['Perte d\'efficacité du freinage', 'Rien de grave', 'Usure des pneus', 'Surchauffe moteur'],
                'correct_option_index' => 0,
                'correct_answer' => 'Perte d\'efficacité du freinage'
            ],
            [
                'question_number' => 11,
                'question_text' => 'Quelles précautions lors du remplissage du réservoir ?',
                'options' => ['Arrêter le moteur, ne pas fumer, ne pas téléphoner', 'Juste arrêter le moteur', 'Juste ne pas fumer', 'Aucune précaution'],
                'correct_option_index' => 0,
                'correct_answer' => 'Arrêter le moteur, ne pas fumer, ne pas téléphoner'
            ],
            [
                'question_number' => 13,
                'question_text' => 'Conséquence d\'une panne de dégivrage de lunette arrière ?',
                'options' => ['Absence de visibilité vers l\'arrière', 'Rien de grave', 'Surchauffe', 'Panne électrique'],
                'correct_option_index' => 0,
                'correct_answer' => 'Absence de visibilité vers l\'arrière'
            ],
            [
                'question_number' => 14,
                'question_text' => 'Que signifie un clignotement plus rapide ?',
                'options' => ['Non fonctionnement d\'une ampoule', 'Batterie faible', 'Fusible grillé', 'Rien d\'anormal'],
                'correct_option_index' => 0,
                'correct_answer' => 'Non fonctionnement d\'une ampoule'
            ],
            [
                'question_number' => 16,
                'question_text' => 'Quand utilise-t-on les feux de brouillard arrière ?',
                'options' => ['Par temps de brouillard et neige', 'Toujours', 'Par forte pluie', 'La nuit'],
                'correct_option_index' => 0,
                'correct_answer' => 'Par temps de brouillard et neige'
            ],
        ];

        foreach ($qserQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $qser->id]));
        }

        // PS Questions with Multiple Choice
        $psQuestions = [
            [
                'question_number' => 1,
                'question_text' => 'Pourquoi protéger une zone de danger en cas d\'accident ?',
                'options' => ['Pour protéger les victimes et éviter un sur-accident', 'Pour faire joli', 'Pour bloquer la route', 'Pour attendre les secours'],
                'correct_option_index' => 0,
                'correct_answer' => 'Pour protéger les victimes et éviter un sur-accident'
            ],
            [
                'question_number' => 2,
                'question_text' => 'Que faire lors d\'une alerte SAIP ?',
                'options' => ['Se mettre en sécurité, s\'informer, respecter les consignes', 'Sortir dehors', 'Appeler tout le monde', 'Rien de spécial'],
                'correct_option_index' => 0,
                'correct_answer' => 'Se mettre en sécurité, s\'informer, respecter les consignes'
            ],
            [
                'question_number' => 6,
                'question_text' => 'Qu\'est-ce qu\'une perte de connaissance ?',
                'options' => ['La victime ne répond pas mais respire', 'La victime ne respire pas', 'La victime parle', 'La victime bouge'],
                'correct_option_index' => 0,
                'correct_answer' => 'La victime ne répond pas mais respire'
            ],
            [
                'question_number' => 10,
                'question_text' => 'Où placer le triangle de pré-signalisation ?',
                'options' => ['À environ 30m de la panne ou avant un virage', 'Juste derrière le véhicule', 'N\'importe où', 'À 100m'],
                'correct_option_index' => 0,
                'correct_answer' => 'À environ 30m de la panne ou avant un virage'
            ],
            [
                'question_number' => 12,
                'question_text' => 'Risques pour une personne inconsciente sur le dos ?',
                'options' => ['Arrêt respiratoire et arrêt cardiaque', 'Rien de grave', 'Mal de dos', 'Froid'],
                'correct_option_index' => 0,
                'correct_answer' => 'Arrêt respiratoire et arrêt cardiaque'
            ],
            [
                'question_number' => 15,
                'question_text' => 'Pourquoi l\'alerte doit-elle être rapide et précise ?',
                'options' => ['Pour apporter les moyens adaptés rapidement', 'Pour faire vite', 'Pour ne pas oublier', 'Pour être efficace'],
                'correct_option_index' => 0,
                'correct_answer' => 'Pour apporter les moyens adaptés rapidement'
            ],
            [
                'question_number' => 17,
                'question_text' => 'Quand mettre une victime en PLS ?',
                'options' => ['Si elle ne répond pas mais respire', 'Si elle parle', 'Si elle bouge', 'Toujours'],
                'correct_option_index' => 0,
                'correct_answer' => 'Si elle ne répond pas mais respire'
            ],
            [
                'question_number' => 20,
                'question_text' => 'Quel est l\'objectif du SAIP ?',
                'options' => ['Avertir d\'un danger imminent', 'Donner la météo', 'Informer des travaux', 'Donner l\'heure'],
                'correct_option_index' => 0,
                'correct_answer' => 'Avertir d\'un danger imminent'
            ],
            [
                'question_number' => 23,
                'question_text' => 'Comment est diffusée l\'alerte SAIP ?',
                'options' => ['Sirènes, médias et application SAIP', 'Seulement sirènes', 'Seulement radio', 'Seulement application'],
                'correct_option_index' => 0,
                'correct_answer' => 'Sirènes, médias et application SAIP'
            ],
            [
                'question_number' => 42,
                'question_text' => 'Risques d\'une hémorragie ?',
                'options' => ['Détresse circulatoire ou arrêt cardiaque', 'Rien de grave', 'Mal de tête', 'Fatigue'],
                'correct_option_index' => 0,
                'correct_answer' => 'Détresse circulatoire ou arrêt cardiaque'
            ],
        ];

        foreach ($psQuestions as $q) {
            QuizQuestion::create(array_merge($q, ['category_id' => $ps->id]));
        }
    }
}
