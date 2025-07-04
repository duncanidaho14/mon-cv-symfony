<?php

namespace App\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ElhadiController extends AbstractController
{
    public function __invoke(): Response
    {
        $achievements = 
        [
            [
                'title' => 'Économies générées chez IAD',
                'value' => '3m€',
                'description' => 'Automatisation des processus CRM',
                'icon' => 'euro'
            ],
            [
                'title' => 'Temps de traitement réduit',
                'value' => '75%',
                'description' => 'Optimisation des workflows',
                'icon' => 'clock'
            ],
            [
                'title' => 'Conseillers impactés',
                'value' => '20,000+',
                'description' => 'Utilisateurs du CRM Playiad',
                'icon' => 'users'
            ],
            [
                'title' => 'Bugs résolus',
                'value' => '450+',
                'description' => 'Maintenance et debugging',
                'icon' => 'bug'
            ],
            [
                'title' => 'APIs développées',
                'value' => '25+',
                'description' => 'Endpoints REST créés',
                'icon' => 'api'
            ],
            [
                'title' => 'Performance améliorée',
                'value' => '60%',
                'description' => 'Temps de réponse optimisé',
                'icon' => 'zap'
            ]
        ];

        $projects = [
            [
                'name' => 'CRM Playiad (IAD)',
                'description' => 'Refonte complète du CRM avec automatisation des tâches',
                'impact' => 'Économie de 3m€/an pour l\'entreprise',
                'tech' => ['Symfony 5', 'ApiPlatform', 'PostgreSQL', 'Docker', 'AWS'],
                'metrics' => [
                    'Réduction du temps de traitement: 75%',
                    'Augmentation de la productivité: 45%',
                    'Satisfaction utilisateur: 92%'
                ],
                'image' => 'build/images/playiad-site-web.png'
            ],
            [
                'name' => 'CityScoring Integration',
                'description' => 'Intégration API pour géolocalisation et prédiction des ventes',
                'impact' => 'Amélioration de 35% de la précision des estimations',
                'tech' => ['PHP', 'RabbitMQ', 'Machine Learning APIs'],
                'metrics' => [
                    'Précision des estimations: +35%',
                    'Temps d\'analyse: -50%',
                    'Rapports générés: 10,000+/mois'
                ],
                'image' => 'build/images/cityscoring.png'
            ],
            [
                'name' => 'Solution CSE Kalliste',
                'description' => 'Application sécurisée pour élections du Comité Social',
                'impact' => 'Digitalisation complète du processus électoral',
                'tech' => ['Symfony 5', 'MySQL', 'Sécurité renforcée'],
                'metrics' => [
                    'Temps de dépouillement: -90%',
                    'Taux de participation: +25%',
                    'Coût opérationnel: -60%'
                ],
                'image' => 'build/images/sas-kalliste-accueil.png'
            ]
        ];

        $skills = [
            'Backend' => [
                'PHP' => 95,
                'Symfony' => 90,
                'ApiPlatform' => 85,
                'C#' => 70,
                'ASP.NET' => 65
            ],
            'Frontend' => [
                'JavaScript' => 80,
                'Vue.js' => 75,
                'Twig' => 90,
                'Sass' => 85,
                'jQuery' => 80
            ],
            'Database' => [
                'PostgreSQL' => 85,
                'MySQL' => 90,
                'Optimisation' => 80
            ],
            'DevOps' => [
                'Docker' => 85,
                'AWS' => 75,
                'Linux' => 80,
                'CI/CD' => 70
            ]
        ];

        $ownProjectPersonnel = [
            [
                'name' => 'GeekBook (Projet Personnel)',
                'description' => 'Achat de livre (cloud).',
                'image' => 'build/images/geekbook-panier.png'
            ],
            [
                'name' => 'GamingGeek (Projet Personnel)',
                'description' => 'Achat et revente de jeux vidéo.',
                'image' => 'build/images/gaminggeek.png'
            ],
            [
                'name' => 'VLAP (Projet Personnel)',
                'description' => 'AirBnB clone.',
                'image' => 'build/images/vlap.png'
            ]
        ];

        $encodedEmail = base64_encode('elhadibeddarem@gmail.com');

        return $this->render('elhadi/index.html.twig', [
            'achievements' => $achievements,
            'projects' => $projects,
            'skills' => $skills,
            'ownProjectPersonnel' => $ownProjectPersonnel,
            'encoded_email' => $encodedEmail
        ]);
    }
}
