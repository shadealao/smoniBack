<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Permis B Classique','Permis B (CS)', 'Permis B (AAC)', 'Location Véhicule', 'Autres'];

        $location = [
            'name' => 'Location Véhicule',
            'services' => [
                [
                    "title" => "Location voiture manuel - sans moniteur SMONI",
                    "type" => "manual",
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
                    "title" => "Location voiture manuel - avec moniteur SMONI",
                    "type" => "manual",
                    "list" => [
                        [
                            "text" => "Durée: 08 - 20h",
                            "valide" => true,
                        ],
                        [
                            "text" => "Accompagnateur SMONI",
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
                    "title" => "Location voiture automatique - sans moniteur SMONI",
                    "type" => "automatique",
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
        ];

        $aac = [
            [
                "title" => "Formule AAC",
                "type" => "manual",
                "list" => [
                    [
                        "text" => "20H de cours de conduite",
                        "valide" => true,
                    ],
                    [
                        "text" => "Gestion de l’élève",
                        "valide" => true,
                    ],
                    [
                        "text" => "Livret d’apprentissage",
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
                "type" => "manual",
                "list" => [
                    [
                        "text" => "30H de cours de conduite",
                        "valide" => true,
                    ],
                    [
                        "text" => "Gestion de l’élève",
                        "valide" => true,
                    ],
                    [
                        "text" => "Livret d’apprentissage",
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
                "title" => "Pack accéléré2-MANUELLE",
                "type" => "manual",
                "list" => [
                    [
                        "text" => "Code",
                        "valide" => true,
                    ],
                    [
                        "text" => "Durée: 30h",
                        "valide" => true,
                    ],
                    [
                        "text" => "1 an de validité",
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
            ]
        ];

// Permis conduire B
$PermisConduireAuto = [  
    [
        "title" => "Leçon de Conduite",
        "price" => 62,
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
        "list" => [
            [
                "text" => "13H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Gestion de l’élève",
                "valide" => true,
            ],
            [
                "text" => "Livret d’apprentissage",
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
        "list" => [
            [
                "text" => "26H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Gestion de l’élève",
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
        "list" => [
            [
                "text" => "13H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Gestion de l’élève",
                "valide" => true,
            ],
            [
                "text" => "Livret d’apprentissage",
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
        "list" => [
            [
                "text" => "26H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Gestion de l’élève",
                "valide" => true,
            ],
            [
                "text" => "Livret d’apprentissage",
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
        "title" => "Pack accéléré 2 automatique",
        "price" => 1990,
        "list" => [
            [
                "text" => "26H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Code",
                "valide" => true,
            ],
            [
                "text" => "1 an de validité",
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
    ]
];

$PermisConduireManu = [
    [
        "title" => "Leçon de Conduite",
        "price" => 62,
        "unit" => "€/h",
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
        "list" => [
            [
                "text" => "20H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Gestion de l’élève",
                "valide" => true,
            ],
            [
                "text" => "Livret d’apprentissage",
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
        "list" => [
            [
                "text" => "30H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Gestion de l’élève",
                "valide" => true,
            ],
            [
                "text" => "Livret d’apprentissage",
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
        "list" => [
            [
                "text" => "20H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Gestion de l’élève",
                "valide" => true,
            ],
            [
                "text" => "Livret d’apprentissage",
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
        "list" => [
            [
                "text" => "30H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Gestion de l’élève",
                "valide" => true,
            ],
            [
                "text" => "Livret d’apprentissage",
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
        "title" => "Pack accéléré 2 - MANUELLE",
        "price" => 2290,
        "list" => [
            [
                "text" => "Code",
                "valide" => true,
            ],
            [
                "text" => "30h de conduite",
                "valide" => true,
            ],
            [
                "text" => "Validité du forfait : 1 an",
                "valide" => true,
            ],
            [
                "text" => "Kilométrage illimité ",
                "valide" => true,
            ],
            [
                "text" => "essence inclus",
                "valide" => true,
            ],
        ],
    ]
];
    
// Permis conduite Supervisé

$PermisSuperviseManuelle = [
    [
    "title" => "Formule Conduite Supervisée",
    "price" => 1230,
    "list" => [
        [
            "text" => "20H de cours de conduite",
            "valide" => true,
        ],
        [
            "text" => "Gestion de l’élève",
            "valide" => true,
        ],
        [
            "text" => "Livret d’apprentissage",
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
        "list" => [
            [
                "text" => "30H de cours de conduite",
                "valide" => true,
            ],
            [
                "text" => "Gestion de l’élève",
                "valide" => true,
            ],
            [
                "text" => "Livret d’apprentissage",
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
        "title" => "pack accéléré 2 manuelle",
        "price" => 1990,
        "list" => [
            [
                "text" => "code",
                "valide" => true,
            ],
            [
                "text" => "30h de conduite",
                "valide" => true,
            ],
            [
                "text" => "1an de validité",
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
    ]
];

$PermisSuperviseAuto = [
    [
        "title" => "Formule base 1 manuelle",
        "price" => 1149,
        "list" => [
            [
                "text" => "code",
                "valide" => true,
            ],
            [
                "text" => "20h de conduite",
                "valide" => true,
            ],
            [
                "text" => "1an de validité",
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
        "title" => "Formule base 2 manuelle",
        "price" => 1549,
        "list" => [
            [
                "text" => "code",
                "valide" => true,
            ],
            [
                "text" => "30h de conduite",
                "valide" => true,
            ],
            [
                "text" => "1an de validité",
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
    ]
];


// Permis AAC
$PermisBAACAuto = [
    [
    "title" => "formule base 1 - AUTOMATIQUE",
    "price" => 855,
    "list" => [
        [
            "text" => "Durée: 13h de conduite",
            "valide" => true,
        ],
        [
            "text" => "Validité du forfait: 1 an",
            "valide" => true,
        ],
        [
            "text" => "Code",
            "valide" => true,
        ],
        [
            "text" => " Km illimité",
            "valide" => true,
        ],
        [
            "text" => "essence inclus",
            "valide" => true,
        ],
    ],
    ],
    [
        "title" => "formule base 2 - AUTOMATIQUE",
        "price" => 1590,
        "list" => [
            [
                "text" => "Durée: 26h de conduite",
                "valide" => true,
            ],
            [
                "text" => "Validité du forfait: 1 an",
                "valide" => true,
            ],
            [
                "text" => "Code",
                "valide" => true,
            ],
            [
                "text" => " Km illimité",
                "valide" => true,
            ],
            [
                "text" => "essence inclus",
                "valide" => true,
            ],
        ],
    ],
    [
        "title" => "pack accéléré 1 - AUTOMATIQUE",
        "price" => 990,
        "list" => [
            [
                "text" => "Durée: 13h de conduite",
                "valide" => true,
            ],
            [
                "text" => "Validité du forfait: 1 an",
                "valide" => true,
            ],
            [
                "text" => "Code",
                "valide" => true,
            ],
            [
                "text" => " Km illimité",
                "valide" => true,
            ],
            [
                "text" => "essence inclus",
                "valide" => true,
            ],
        ],
    ],
    [
        "title" => "pack accéléré 2 - AUTOMATIQUE",
        "price" => 1849,
        "list" => [
            [
                "text" => "Durée: 26h de conduite",
                "valide" => true,
            ],
            [
                "text" => "Validité du forfait: 1 an",
                "valide" => true,
            ],
            [
                "text" => "Code",
                "valide" => true,
            ],
            [
                "text" => " Km illimité",
                "valide" => true,
            ],
            [
                "text" => "essence inclus",
                "valide" => true,
            ],
        ],
    ]
];

$PermisBAACManu = [
    [
    "title" => "formule base 2 - MANUELLE",
    "price" => 1149,
    "list" => [
        [
            "text" => "Durée: 20h de conduite",
            "valide" => true,
        ],
        [
            "text" => "Validité du forfait: 1 an",
            "valide" => true,
        ],
        [
            "text" => "Code",
            "valide" => true,
        ],
        [
            "text" => " Km illimité",
            "valide" => true,
        ],
        [
            "text" => "essence inclus",
            "valide" => true,
        ],
    ],
    ],
    [
        "title" => "formule base 2 - MANUELLE",
        "price" => 1549,
        "list" => [
            [
                "text" => "Durée: 30h de conduite",
                "valide" => true,
            ],
            [
                "text" => "Validité du forfait: 1 an",
                "valide" => true,
            ],
            [
                "text" => "Code",
                "valide" => true,
            ],
            [
                "text" => " Km illimité",
                "valide" => true,
            ],
            [
                "text" => "essence inclus",
                "valide" => true,
            ],
        ],
    ],
    [
        "title" => "pack accéléré 1 - MANUELLE",
        "price" => 1509,
        "list" => [
            [
                "text" => "Durée: 20h de conduite",
                "valide" => true,
            ],
            [
                "text" => "Validité du forfait: 1 an",
                "valide" => true,
            ],
            [
                "text" => "Code",
                "valide" => true,
            ],
            [
                "text" => " Km illimité",
                "valide" => true,
            ],
            [
                "text" => "essence inclus",
                "valide" => true,
            ],
        ],
    ],
    [
        "title" => "pack accéléré 2 - MANUELLE",
        "price" => 1990,
        "list" => [
            [
                "text" => "Durée: 30h de conduite",
                "valide" => true,
            ],
            [
                "text" => "Validité du forfait: 1 an",
                "valide" => true,
            ],
            [
                "text" => "Code",
                "valide" => true,
            ],
            [
                "text" => " Km illimité",
                "valide" => true,
            ],
            [
                "text" => "essence inclus",
                "valide" => true,
            ],
        ],
    ]
];


$AccompagnementExamen = [
    "title" => "Accompagnement à l'examen",
    "price" => 89.9,
    "list" => [
        [
            "text" => "1h de cour",
            "valide" => true,
        ],
        [
            "text" => "Location véhicule",
            "valide" => true,
        ],
        [
            "text" => "Accompagnateur SMONI",
            "valide" => true,
        ],
        [
            "text" => "1mois de validité",
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
];

$PasserelleData = [
    "title" => "Passerelle",
    "price" => 499.9,
    "list" => [
        [
            "text" => "1h de conduite",
            "valide" => true,
        ],
        [
            "text" => "Boite AUTOMATIQUE vers boite MANUELLE",
            "valide" => true,
        ],
        [
            "text" => "Délivrance de maîtrise de la boîte MANUELLE",
            "valide" => true,
        ],
        [
            "text" => "Franchise maline 150€ + 10.00€",
            "valide" => true,
        ],
        [
            "text" => "6mois de validité",
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
        [
            "text" => "fiche de suivie : uniquement la compétence C1f",
            "valide" => true,
        ],
    ],
];

$ExtensionContrat = [
    "title" => "Extension contrat",
    "price" => 299,
    "list" => [ ],
];

$CodeEnLigne = [
    "title" => "Code",
    "price" => 30,
    "list" => [
        [
            "text" => "Code en ligne",
            "valide" => true,
        ],
        [
            "text" => "4 mois de validité",
            "valide" => true,
        ],
    ],
];

$ExamenCode = [
    "title" => "Examen code",
    "price" => 30,
    "list" => [ ],
];

$FabricationPermis = [
    "title" => "Fabrication Permis",
    "price" => 30,
    "list" => [ ],
];

$Passerelle = [
    "title" => "Passerelle",
    "price" => 430,
    "list" => [
        [
            "text" => "7H de cours",
            "valide" => true,
        ],
        [
            "text" => "Formation boite automatique vers boite automatique",
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
];

$PackCode = [
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
];

$AccompagnementExamenPratique = [
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
];


    

        foreach ($categories as $category) {
            $category_service = CategoryService::firstOrCreate(
                ['label' => $category],
                ['label' => $category]
            );
        }
        
    }
}
