<?php

namespace Database\Seeders;

use App\Models\CategoryService;
use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clean start to avoid duplicate IDs/Labels
        Schema::disableForeignKeyConstraints();
        CategoryService::truncate();
        Service::truncate();
        ServiceItem::truncate();
        Schema::enableForeignKeyConstraints();

        $categories = [
            [
                'name' => 'Permis B Classique',
                'services' => [  
                    ["title" => "Leçon de Conduite", "price" => 62, "type" => "automatic", "list" => [
                        ["text" => "1H de cours de conduite", "valide" => true],
                        ["text" => "1 mois de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Perfectionnement", "price" => 490, "type" => "automatic", "list" => [
                        ["text" => "10H de cours de conduite", "valide" => true],
                        ["text" => "6 mois de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Classique", "price" => 890, "type" => "automatic", "list" => [
                        ["text" => "13H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "1 an de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Classique PLUS", "price" => 1750, "type" => "automatic", "list" => [
                        ["text" => "26H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "1 an de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Accéléré", "price" => 998, "type" => "automatic", "list" => [
                        ["text" => "13H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "1 an de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Accéléré PLUS", "price" => 1990, "type" => "automatic", "list" => [
                        ["text" => "26H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "1 an de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    // MANUAL
                    ["title" => "Leçon de Conduite", "price" => 62, "type" => "manual", "list" => [
                        ["text" => "1H de cours de conduite", "valide" => true],
                        ["text" => "1 mois de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Perfectionnement", "price" => 490, "type" => "manual", "list" => [
                        ["text" => "10H de cours de conduite", "valide" => true],
                        ["text" => "6 mois de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Classique", "price" => 1130, "type" => "manual", "list" => [
                        ["text" => "20H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "1 an de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Classique PLUS", "price" => 1690, "type" => "manual", "list" => [
                        ["text" => "30H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "1 an de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Accéléré", "price" => 1590, "type" => "manual", "list" => [
                        ["text" => "20H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "1 an de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Accéléré PLUS", "price" => 2290, "type" => "manual", "list" => [
                        ["text" => "30H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "1 an de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                ]
            ],       
            [
                'name' => 'Permis B (CS)',
                'services' => [
                    ["title" => "Formule Conduite Supervisée", "price" => 1230, "type" => "automatic", "list" => [
                        ["text" => "13H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "3 ans de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Conduite Supervisée PLUS", "price" => 1790, "type" => "automatic", "list" => [
                        ["text" => "26H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "3 ans de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Conduite Supervisée", "price" => 1230, "type" => "manual", "list" => [
                        ["text" => "20H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "3 ans de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule Conduite Supervisée PLUS", "price" => 1790, "type" => "manual", "list" => [
                        ["text" => "30H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "3 ans de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                ]
            ],
            [
                'name' => 'Permis B (AAC)',
                'services' => [
                    ["title" => "Formule AAC", "price" => 1230, "type" => "automatic", "list" => [
                        ["text" => "13H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "3 ans de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule AAC PLUS", "price" => 1790, "type" => "automatic", "list" => [
                        ["text" => "26H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "3 ans de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule AAC", "price" => 1230, "type" => "manual", "list" => [
                        ["text" => "20H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "3 ans de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formule AAC PLUS", "price" => 1790, "type" => "manual", "list" => [
                        ["text" => "30H de cours de conduite", "valide" => true],
                        ["text" => "Gestion de l'élève", "valide" => true],
                        ["text" => "Livret d'apprentissage", "valide" => true],
                        ["text" => "3 ans de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                ]
            ],
            [
                'name' => 'Location Véhicule',
                'services' => [
                    ["title" => "Location voiture manuel - sans moniteur SMONI", "type" => "manual", "price" => 99.9, "list" => [
                        ["text" => "Durée: 08 - 20h", "valide" => true],
                        ["text" => "Accompagnateur externe", "valide" => true],
                        ["text" => "1 mois de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => false],
                    ]],
                    ["title" => "Location voiture automatic - sans moniteur SMONI", "type" => "automatic", "price" => 99, "list" => [
                        ["text" => "Durée: 08 - 20h", "valide" => true],
                        ["text" => "Accompagnateur externe", "valide" => true],
                        ["text" => "1 mois de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => false],
                    ]],
                ]
            ],
            [
                'name' => 'Autres',
                'services' => [
                    ["title" => "Fabrication Permis", "price" => 30, "list" => []],
                    ["title" => "Extension contrat", "price" => 249, "list" => []],
                    ["title" => "Examen code", "price" => 30, "list" => []],
                    ["title" => "Accompagnement", "price" => 247, "list" => [
                        ["text" => "Frais de dossier - 89€", "valide" => true],
                        ["text" => "Location du véhicule - 99€", "valide" => true],
                        ["text" => "Prestation accompagnateur - 59€", "valide" => true],
                        ["text" => "Véhicule double commande", "valide" => true],
                        ["text" => "Accompagnateur qualifié", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Pack code", "price" => 249, "list" => [
                        ["text" => "Accès illimité à la salle de code durant 4 mois", "valide" => true],
                        ["text" => "Code en ligne ou en salle valable 4 mois", "valide" => true],
                        ["text" => "Livre de Code", "valide" => true],
                        ["text" => "Contrôle de connaissance", "valide" => true],
                    ]],
                ]
            ],
            [
                'name' => 'CPF',
                'services' => [],
            ],
            [
                'name' => 'Location double Commande aux professionnels',
                'services' => [
                    ["title" => "Location double commande - Manuel", "type" => "manual", "price" => 150, "list" => [
                        ["text" => "Véhicule double commande", "valide" => true],
                        ["text" => "Usage professionnel", "valide" => true],
                    ]],
                    ["title" => "Location double commande - Automatique", "type" => "automatic", "price" => 160, "list" => [
                        ["text" => "Véhicule double commande", "valide" => true],
                        ["text" => "Usage professionnel", "valide" => true],
                    ]],
                ]
            ],
            [
                'name' => 'Passerelle',
                'services' => [
                    ["title" => "Formation Passerelle 7h", "type" => "automatic", "price" => 549, "list" => [
                        ["text" => "Formation de 7h - 430€", "valide" => true],
                        ["text" => "Frais de dossier - 89€", "valide" => true],
                        ["text" => "Fabrication du permis - 30€", "valide" => true],
                        ["text" => "Boite AUTOMATIQUE vers boite MANUELLE", "valide" => true],
                        ["text" => "6 mois de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                    ["title" => "Formation Passerelle 7h", "type" => "manual", "price" => 549, "list" => [
                        ["text" => "Formation de 7h - 430€", "valide" => true],
                        ["text" => "Frais de dossier - 89€", "valide" => true],
                        ["text" => "Fabrication du permis - 30€", "valide" => true],
                        ["text" => "Boite AUTOMATIQUE vers boite MANUELLE", "valide" => true],
                        ["text" => "6 mois de validité", "valide" => true],
                        ["text" => "Kilométrage illimité", "valide" => true],
                        ["text" => "Essence inclus", "valide" => true],
                    ]],
                ]
            ],
            [
                'name' => 'Post-Permis',
                'services' => [
                    ["title" => "Formation Post-Permis", "type" => "automatic", "price" => 100, "list" => [
                        ["text" => "Formation théorique de 7h", "valide" => true],
                        ["text" => "Réduction période probatoire", "valide" => true],
                        ["text" => "Échanges entre conducteurs", "valide" => true],
                        ["text" => "Uniquement après 6-12 mois", "valide" => true],
                    ]],
                    ["title" => "Formation Post-Permis", "type" => "manual", "price" => 100, "list" => [
                        ["text" => "Formation théorique de 7h", "valide" => true],
                        ["text" => "Réduction période probatoire", "valide" => true],
                        ["text" => "Échanges entre conducteurs", "valide" => true],
                        ["text" => "Uniquement après 6-12 mois", "valide" => true],
                    ]],
                ]
            ],
        ];

        foreach ($categories as $category) {
            $category_service = CategoryService::create(['label' => $category['name']]);
            foreach ($category['services'] as $sub) {
                $service = Service::create([
                    'category_service_id' => $category_service->id,
                    'title' => $sub['title'],
                    'price' => $sub['price'],
                    'type' => $sub['type'] ?? 'manual',
                    'status' => true,  // THIS IS THE KEY FIX
                ]);
                foreach ($sub['list'] ?? [] as $list) {
                    ServiceItem::create([
                        'service_id' => $service->id,
                        'label' => $list['text'], 
                        'status' => $list['valide'],
                    ]);
                }
            }
        }
    }
}
