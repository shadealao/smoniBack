<?php

namespace Database\Seeders;

use App\Models\TrainingModule;
use App\Models\StepModuleItem;
use App\Models\ModuleStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'code' => 'C1',
                'name' => 'Maîtriser le maniement du véhicule dans un trafic faible ou nul',
                'description' => 'Compétence de base pour la manipulation du véhicule',
                'duration_hours' => 20,
                'required_for_license' => true,
                'display_order' => 1,
                'step_module' => [
                    [
                        'code' => 'C1a',
                        'name' => 'Connaître les principaux organes et commandes du véhicule, effectuer des vérifications intérieures et extérieures.',
                        'description' => [
                            'Connaître les principaux organes du véhicule : moteur, transmission, freinage, direction, suspension, fusibles.',
                            'Connaître les commandes du véhicule : volant, pédales, levier de vitesses, frein à main.',
                            'Connaître les différents témoins, indicateurs et commandes.',
                            'Vérifier le fonctionnement des témoins, indicateurs et commandes (activer et désactiver l\'airbag).',
                            'Vérifier la propreté, le fonctionnement et l\'état des différents systèmes d\'éclairage ainsi que la propreté et l\'état des rétroviseurs.',
                            'Vérifier l\'état, l\'usure et la pression des pneumatiques.',
                        ],
                    ],
                    [
                        'code' => 'C1b',
                        'name' => 'Entrer, s\'installer au poste de conduite et en sortir.',
                        'description' => [
                            'Connaître les précautions pour monter dans le véhicule.',
                            'Effectuer le réglage du siège (hauteur, longueur, dossier).',
                            'Effectuer le réglage du volant (hauteur, profondeur).',
                            'Effectuer le réglage des rétroviseurs.',
                            'Positionner la ceinture de sécurité.',
                            'Connaître les précautions pour installer des passagers.',
                            'Connaître les précautions pour transporter des bagages.',
                            'Connaître les précautions pour sortir du véhicule.',
                        ],
                    ],
                    [
                        'code' => 'C1c',
                        'name' => 'Tenir, tourner le volant et maintenir la trajectoire.',
                        'description' => [
                            'Savoir tenir le volant en ligne droite.',
                            'Savoir le tourner sans déplacer les mains sur la couronne.',
                            'Savoir le tourner avec simple déplacement des mains.',
                            'Savoir placer le regard pour maintenir la trajectoire.',
                        ],
                    ],
                    [
                        'code' => 'C1d',
                        'name' => 'Démarrer et s\'arrêter.',
                        'description' => [
                            'Enlever et remettre l\'antivol de direction.',
                            'Mettre en route le moteur et l\'arrêter.',
                            'Démarrer sur terrain plat.',
                            'Démarrer en descente.',
                            'Démarrer en montée.',
                            'Arrêter la voiture à un endroit précis.',
                            'Identifier les causes d\'un calage et remettre en route rapidement le moteur (après un calage).',
                        ],
                    ],
                    [
                        'code' => 'C1e',
                        'name' => 'Doser l\'accélération et le freinage à diverses allures.',
                        'description' => [
                            'Doser l\'accélération à diverses allures.',
                            'Doser le freinage à diverses allures.',
                            'Utiliser le frein moteur.',
                        ],
                    ],
                    [
                        'code' => 'C1f',
                        'name' => 'Utiliser la boîte de vitesses.',
                        'description' => [
                            'Connaître le principe de fonctionnement de la boîte de vitesses.',
                            'Manipuler le levier de vitesses.',
                            'Monter les vitesses.',
                            'Rétrograder les vitesses.',
                        ],
                    ],
                    [
                        'code' => 'C1g',
                        'name' => 'Diriger la voiture en avant en ligne droite et en courbe en adaptant allure et trajectoire.',
                        'description' => [
                            'Savoir déplacer le regard afin de maintenir une trajectoire rectiligne.',
                            'Contrôler la direction en fonction de la rotation du volant.',
                            'Adapter l\'allure.',
                            'Adapter la trajectoire.',
                        ],
                    ],
                    [
                        'code' => 'C1h',
                        'name' => 'Regarder autour de soi et avertir.',
                        'description' => [
                            'Connaître les angles morts.',
                            'Avoir une mobilité du regard.',
                            'Regarder dans les rétroviseurs.',
                            'Faire fonctionner les clignotants, le signal de détresse, les feux stop par intermittence, les feux et l\'avertisseur sonore.',
                            'Choisir le moment et la façon d\'avertir.',
                        ],
                    ],
                    [
                        'code' => 'C1i',
                        'name' => 'Effectuer une marche arrière et un demi-tour en sécurité.',
                        'description' => [
                            'Se positionner correctement : dos, bras, mains, jambes.',
                            'Observer autour de soi.',
                            'Communiquer avec les autres usagers.',
                            'Adapter l\'allure.',
                        ],
                    ],
                ],
            ],

            [
                'code' => 'C2',
                'name' => 'Appréhender la route et circuler dans des conditions normales',
                'description' => 'Compétence pour la circulation en conditions normales',
                'duration_hours' => 15,
                'required_for_license' => true,
                'display_order' => 2,
                'step_module' => [
                    [
                        'code' => 'C2a',
                        'name' => 'Rechercher la signalisation, les indices utiles et en tenir compte.',
                        'description' => [
                            'Détecter la signalisation horizontale et verticale.',
                            'Reconnaître et interpréter la signalisation verticale et horizontale.',
                            'Découvrir les indices utiles permettant d\'anticiper.',
                            'Tenir compte des indices utiles.',
                        ],
                    ],
                    [
                        'code' => 'C2b',
                        'name' => 'Positionner le véhicule sur la chaussée et choisir la voie de circulation.',
                        'description' => [
                            'Tenir compte des marquages au sol.',
                            'Choisir la voie de circulation.',
                            'Positionner le véhicule en fonction des directions prises.',
                            'Connaître et respecter les affectations des voies réservées et spécialisées.',
                        ],
                    ],
                    [
                        'code' => 'C2c',
                        'name' => 'Adapter l\'allure aux situations.',
                        'description' => [
                            'Adapter l\'allure en fonction de la signalisation et de la réglementation.',
                            'Adapter l\'allure en fonction de la visibilité et du relief.',
                            'Adapter l\'allure en fonction de la présence d\'autres usagers.',
                            'Adapter l\'allure en fonction des conditions météorologiques.',
                            'Adapter l\'allure en fonction des possibilités du véhicule.',
                            'Adapter l\'allure en fonction de ses capacités.',
                        ],
                    ],
                    [
                        'code' => 'C2d',
                        'name' => 'Détecter, identifier et franchir les intersections suivant le régime de priorité.',
                        'description' => [
                            'Détecter les intersections.',
                            'Identifier le type d\'intersection.',
                            'Évaluer la visibilité.',
                            'Tenir compte des autres usagers, visibles ou non.',
                            'Adapter l\'allure en fonction du régime de priorité, de la visibilité et de la présence éventuelle d\'usagers.',
                            'Se décider au moment de l\'application des règles de sécurité.',
                        ],
                    ],
                    [
                        'code' => 'C2e',
                        'name' => 'Tourner à droite et à gauche en agglomération.',
                        'description' => [
                            'Observer la signalisation et en tenir compte.',
                            'Observer les autres usagers pour ne pas les gêner ou les surprendre.',
                            'Signaler le changement de direction.',
                            'Positionner le véhicule en fonction de la direction souhaitée.',
                            'Adapter l\'allure et la trajectoire.',
                            'Respecter le régime de priorité.',
                        ],
                    ],
                    [
                        'code' => 'C2f',
                        'name' => 'Franchir les ronds-points et les carrefours à sens giratoire.',
                        'description' => [
                            'Connaître la signalisation spécifique.',
                            'Identifier les deux types d\'infrastructure.',
                            'Observer les autres usagers pour ne pas les gêner ou les surprendre.',
                            'Signaler les changements de direction.',
                            'Positionner le véhicule en fonction de la direction souhaitée.',
                            'Adapter l\'allure et la trajectoire.',
                            'Respecter le régime de priorité.',
                        ],
                    ],
                    [
                        'code' => 'C2g',
                        'name' => 'S\'arrêter et stationner en épi, en bataille et en créneau.',
                        'description' => [
                            'Comprendre la réglementation de l\'arrêt et du stationnement sur route et en agglomération.',
                            'Observer les autres usagers pour ne pas les gêner ou les surprendre.',
                            'Signaler son intention.',
                            'Se ranger en épi en sécurité.',
                            'Se ranger en bataille en sécurité.',
                            'Se ranger en créneau en sécurité.',
                            'Sortir d\'un stationnement en sécurité.',
                        ],
                    ],
                ],
            ],

            [
                'code' => 'C3',
                'name' => 'Circuler dans les conditions difficiles et partager la route avec les autres usagers',
                'description' => 'Compétence pour la circulation en conditions normales',
                'duration_hours' => 15,
                'required_for_license' => true,
                'display_order' => 2,
                'step_module' => [
                    [
                        'code' => 'C3a',
                        'name' => 'Évaluer et maintenir les distances de sécurité.',
                        'description' => [
                            'Comprendre l\'importance des distances de sécurité latérales et longitudinales.',
                            'Évaluer la distance longitudinale existant entre la voiture et celle qu\'on suit ou qu\'on précède.',
                            'Évaluer les distances latérales en circulant, croisant ou dépassant.',
                            'Calculer la distance minimale de sécurité en fonction de la vitesse.',
                            'Respecter, de manière automatisée, les distances de sécurité.',
                        ],
                    ],
                    [
                        'code' => 'C3b',
                        'name' => 'Croiser, dépasser, être dépassé.',
                        'description' => [
                            'Évaluer le gabarit du véhicule en largeur et en longueur.',
                            'Comprendre la réglementation concernant le croisement.',
                            'Évaluer les difficultés d\'un croisement.',
                            'Croiser dans de bonnes conditions de sécurité.',
                            'Comprendre la réglementation concernant le dépassement.',
                            'Choisir le moment et l\'endroit pour dépasser.',
                            'Utiliser les capacités d\'accélération de la voiture pour dépasser.',
                            'Choisir le moment pour se rabattre.',
                            'Renoncer au dépassement.',
                            'Faciliter le dépassement.',
                        ],
                    ],
                    [
                        'code' => 'C3c',
                        'name' => 'Passer des virages et conduire en déclivité.',
                        'description' => [
                            'Comprendre la signalisation des virages.',
                            'Évaluer les difficultés d\'un virage (rayon, profil, visibilité, état du revêtement).',
                            'Diriger le regard.',
                            'Adapter l\'allure et la trajectoire (avant, pendant et après le virage).',
                            'Connaître les risques liés au freinage en virage et à la force centrifuge.',
                            'Connaître les risques liés à l\'échauffement des freins.',
                            'Savoir utiliser le frein moteur.',
                        ],
                    ],
                    [
                        'code' => 'C3d',
                        'name' => 'Connaître les caractéristiques des autres usagers et savoir se comporter à leur égard avec respect et courtoisie.',
                        'description' => [
                            'Connaître les particularités des piétons.',
                            'Connaître les particularités des véhicules à deux-roues.',
                            'Connaître les particularités des véhicules lents et/ou encombrants.',
                            'Connaître les particularités des véhicules de transport en commun.',
                            'Connaître les particularités des véhicules d\'intérêt général.',
                            'Savoir se comporter avec respect et courtoisie à l\'égard des diverses catégories d\'usagers.',
                        ],
                    ],
                    [
                        'code' => 'C3e',
                        'name' => 'S\'insérer, circuler et sortir d\'une voie rapide.',
                        'description' => [
                            'Observer les autres usagers.',
                            'Évaluer les distances et les vitesses des autres.',
                            'Signaler au moment opportun.',
                            'Contrôler l\'allure.',
                            'Utiliser une voie d\'insertion et/ou une voie d\'entrecroisement en sécurité.',
                            'Sortir d\'une voie rapide en sécurité.',
                        ],
                    ],
                    [
                        'code' => 'C3f',
                        'name' => 'Conduire dans une file de véhicules et dans une circulation dense.',
                        'description' => [
                            'Comprendre la réglementation concernant la circulation en file.',
                            'Choisir sa file et rester à sa place.',
                            'Maintenir les distances de sécurité.',
                            'Préparer un changement de direction vers la droite ou vers la gauche.',
                            'Détecter les indices permettant de prévoir les mouvements des autres usagers.',
                            'Anticiper pour éviter les changements d\'allure soudains.',
                        ],
                    ],
                ],
            ],

            [
                'code' => 'C4',
                'name' => 'Pratiquer une conduite autonome, sûre, et économique',
                'description' => 'Compétence pour la circulation en conditions normales',
                'duration_hours' => 15,
                'required_for_license' => true,
                'display_order' => 2,
                'step_module' => [
                    [
                        'code' => 'C4a',
                        'name' => 'Suivre un itinéraire de manière autonome.',
                        'description' => [
                            'Connaître les différentes signalisations de direction.',
                            'Lire et interpréter la signalisation de direction.',
                            'Suivre l\'itinéraire de manière autonome.',
                            'Savoir modifier son itinéraire de façon autonome suite à des bouchons, travaux, etc.',
                        ],
                    ],
                    [
                        'code' => 'C4b',
                        'name' => 'Préparer et effectuer un voyage longue distance en autonomie.',
                        'description' => [
                            'Préparer l\'itinéraire (lecture d\'une carte, site Internet...).',
                            'Prévoir l\'heure de départ, les pauses, les étapes et partir reposé.',
                            'S\'informer sur les conditions de parcours (densité de circulation, conditions météorologiques).',
                            'Vérifier les éléments de sécurité du véhicule (feux, pneus, freins...).',
                            'Paramétrer éventuellement un G.P.S. avant de partir.',
                            'Lire la signalisation de direction.',
                            'Gérer les temps de conduite et les temps de pause lors du voyage.',
                        ],
                    ],
                    [
                        'code' => 'C4c',
                        'name' => 'Connaître les principaux facteurs de risque au volant et les recommandations à appliquer.',
                        'description' => [
                            'Les risques liés à la fatigue.',
                            'Les risques liés à la consommation de produits psychoactifs (alcool, stupéfiants, médicaments).',
                            'Les risques liés à la vitesse.',
                            'Les risques liés à l\'utilisation du téléphone portable en roulant.',
                            'Les risques liés à l\'habitude (trajets connus).',
                            'Les précautions à prendre pour chacune des situations à risque.',
                        ],
                    ],
                    [
                        'code' => 'C4d',
                        'name' => 'Connaître les comportements à adapter en cas d\'accident : protéger, alerter, secourir.',
                        'description' => [
                            'Savoir protéger (baliser).',
                            'Savoir alerter.',
                            'Avoir des notions de secourisme.',
                            'Connaître la réglementation relative au délit de fuite et à la non-assistance à personne en danger.',
                            'Savoir remplir un constat amiable.',
                        ],
                    ],
                    [
                        'code' => 'C4e',
                        'name' => 'Faire l\'expérience des aides à la conduite du véhicule (régulateur, limiteur de vitesse, ABS, aides à la navigation...).',
                        'description' => [
                            'Avoir des notions sur les principales aides à la conduite.',
                            'Comprendre l\'intérêt de l\'utilisation de ces dispositifs.',
                            'Comprendre les limites de ces dispositifs.',
                            'Faire l\'expérience des principales aides à la conduite.',
                        ],
                    ],
                    [
                        'code' => 'C4f',
                        'name' => 'Avoir des notions sur l\'entretien, le dépannage et les situations d\'urgence.',
                        'description' => [
                            'Savoir trouver les informations relatives à l\'entretien et/ou dépannage du véhicule.',
                            'Comprendre l\'importance d\'un entretien régulier du véhicule.',
                            'Vérifier les différents niveaux et les compléter éventuellement.',
                            'Savoir quand vérifier et comment ajuster la pression des pneus.',
                            'Prendre les précautions en cas de panne.',
                            'Effectuer certains dépannages (changement d\'une roue, d\'un fusible, d\'une ampoule...).',
                            'Freiner en situation d\'urgence.',
                            'Dégager une intersection ou un passage à niveau en cas de problème moteur.',
                            'Savoir que faire en cas d\'incendie.',
                        ],
                    ],
                    [
                        'code' => 'C4g',
                        'name' => 'Pratiquer l\'écoconduite.',
                        'description' => [
                            'Comprendre les effets des émissions polluantes (atmosphériques et sonores).',
                            'Comprendre les avantages de l\'écoconduite.',
                            'Connaître les principes de l\'écoconduite.',
                            'Anticiper les situations de conduite.',
                        ],
                    ],
                ]
            ],
        ];

        foreach ($modules as $module) {
            $traning= TrainingModule::firstOrCreate(
                [
                    'code' => $module['code']
                ],
                [
                'code' => $module['code'],
                'name' => $module['name'],
                'description' => $module['description'],
                'duration_hours' => $module['duration_hours'],
                'required_for_license' => $module['required_for_license'],
                'display_order' => $module['display_order'],
                
            ]);

            foreach ($module['step_module'] as $step) {
                $moduleStep= ModuleStep::firstOrCreate(
                    [
                        'code' => $step['code']
                    ],
                    [
                    'code' => $step['code'],
                    'name' => $step['name'],
                    'module_id' => $traning->id,
                ]);

                foreach ($step['description'] as $describ) {
                    $stepModuleItem= StepModuleItem::firstOrCreate(
                        [
                            'description' => $describ,
                        ],
                        [
                        'description' => $describ,
                        'step_id' => $moduleStep->id,
                    ]);
                }
            }


        }
    }
}
