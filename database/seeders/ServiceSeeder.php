<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CategoryService;
use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Permis B Classique',
                'services' => [  
                    [
                        "title" => "Leçon de Conduite",
                        "price" => 62,
                        "type" => "automatic",
                        "list" => [
                            [
                                "text" => "1H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 mois de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Perfectionnement",
                        "price" => 490,
                        "type" => "automatic",
                        "list" => [
                            [
                                "text" => "10H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "6 mois de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule Classique",
                        "price" => 890,
                        "type" => "automatic",
                        "list" => [
                            [
                                "text" => "13H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule Classique PLUS",
                        "price" => 1750,
                        "type" => "automatic",
                        "list" => [
                            [
                                "text" => "26H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule Accéléré",
                        "price" => 998,
                        "type" => "automatic",
                        "list" => [
                            [
                                "text" => "13H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule Accéléré PLUS",
                        "price" => 1990,
                        "type" => "automatic",
                        "list" => [
                            [
                                "text" => "26H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Leçon de Conduite",
                        "price" => 62,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "1H Cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 mois de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Perfectionnement",
                        "price" => 490,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "10H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "6 mois de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule classique",
                        "price" => 1130,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "20H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule Classique PLUS",
                        "price" => 1690,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "30H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule Accéléré",
                        "price" => 1590,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "20H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule Accéléré PLUS",
                        "price" => 2290,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "30H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ]
                ]
            ],       
            [
                'name' => 'Permis B (CS)',
                'services' => [
                    [
                        "title" => "Formule Conduite Supervisée",
                        "price" => 1230,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "20H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "3 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule Conduite Supervisée PLUS",
                        "price" => 1790,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "30H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "3 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ]
                ]
            ],
            [
                'name' => 'Permis B (AAC)',
                'services' => [
                    [
                        "title" => "Formule AAC",
                        "price" => 1230,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "20H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "3 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Formule base2-MANUELLE",
                        "price" => 1790,
                        "type" => "manual",
                        "list" => [
                            [
                                "text" => "30H de cours de conduite",
                                "valide" => true,
                            ],
                            [
                                "text" => "Gestion de l'élève",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livret d'apprentissage",
                                "valide" => true,
                            ],
                            [
                                "text" => "3 an de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ]
                ]
            ],
            [
                'name' => 'Location Véhicule',
                'services' => [
                    [
                        "title" => "Location voiture manuel - sans moniteur SMONI",
                        "type" => "manual",
                        "price" => 99.9,
                        "list" => [
                            [
                                "text" => "Durée: 08 - 20h",
                                "valide" => true,
                            ],
                            [
                                "text" => "Accompagnateur externe",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 mois de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => false,
                            ],
                        ],
                    ],
                    [
                        "title" => "Location voiture automatic - sans moniteur SMONI",
                        "type" => "automatic",
                        "price" => 99,
                        "list" => [
                            [
                                "text" => "Durée: 08 - 20h",
                                "valide" => true,
                            ],
                            [
                                "text" => "Accompagnateur externe",
                                "valide" => true,
                            ],
                            [
                                "text" => "1 mois de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => false,
                            ],
                        ],
                    ],
                ]
            ],
            [
                'name' => 'Autres',
                'services' => [
                    [
                        "title" => "Fabrication Permis",
                        "price" => 30,
                        "list" => [ ],
                    ],
                    [
                        "title" => "Extension contrat",
                        "price" => 299,
                        "list" => [ ],
                    ],
                    [
                        "title" => "Examen code",
                        "price" => 30,
                        "list" => [ ],
                    ],
                    [
                        "title" => "Accompagnement à l'examen pratique",
                        "price" => 89,
                        "list" => [
                            [
                                "text" => "1H de cours",
                                "valide" => true,
                            ],
                            [
                                "text" => "location du véhicule",
                                "valide" => true,
                            ],
                            [
                                "text" => "accompagnateur SMONI",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Passerelle",
                        "price" => 430,
                        "list" => [
                            [
                                "text" => "7H de cours",
                                "valide" => true,
                            ],
                            [
                                "text" => "Formation boite automatic vers boite automatic",
                                "valide" => true,
                            ],
                            [
                                "text" => "Délivrance de la maitrise de la boite manuelle",
                                "valide" => true,
                            ],
                            [
                                "text" => "6 mois de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "kilométrage illimité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Essence inclus",
                                "valide" => true,
                            ],
                        ],
                    ],
                    [
                        "title" => "Pack code",
                        "price" => 249,
                        "list" => [
                            [
                                "text" => "Accès illimité (cours thématique + controle de connaisance) à la salle de code durant 4 mois",
                                "valide" => true,
                            ],
                            [
                                "text" => "Code en ligne ou en salle valable 4mois",
                                "valide" => true,
                            ],
                            [
                                "text" => "Délivrance de la maitrise de la boite manuelle",
                                "valide" => true,
                            ],
                            [
                                "text" => "4 mois de validité",
                                "valide" => true,
                            ],
                            [
                                "text" => "Livre de Code",
                                "valide" => true,
                            ],
                            [
                                "text" => "Contrôle de connaissance du Code en ligne (e-learning)",
                                "valide" => true,
                            ],
                        ],
                    ]
                ]
            ]
        ];

        foreach ($categories as $category) {
            $category_service = CategoryService::firstOrCreate(
                ['label' => $category['name']],
                ['label' => $category['name']]
            );

            foreach ($category['services'] as $sub) {
                $service = Service::firstOrCreate(
                    [
                        'category_service_id' => $category_service->id,
                        'title' => $sub['title'],
                        'price' => $sub['price'],
                        'type' => isset($sub['type']) ? $sub['type'] : 'manual',
                    ],
                    [
                        'category_service_id' => $category_service->id,
                        'title' => $sub['title'],
                        'price' => $sub['price'],
                        'type' => isset($sub['type']) ? $sub['type'] : 'manual',
                    ],
                );
                foreach ($sub['list'] as $list) {
                    $item = ServiceItem::firstOrCreate(
                        [
                            'service_id' => $service->id,
                            'label' => $list['text'], 
                            'status' => $list['valide'],
                        ],
                        [
                            'service_id' => $service->id,
                            'label' => $list['text'], 
                            'status' => $list['valide'],
                        ],
                    );
                }
            }
        }
        
    }
}
