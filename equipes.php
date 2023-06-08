<?php

$token = '1bf86dc9d65746149a26808fdc45a5a1';
$baseURL = 'https://api.football-data.org/v2/';

// Endpoint pour obtenir les informations sur la Premier League
$endpoint = 'competitions/PL/standings';

// Construction de l'URL complète
$url = $baseURL . $endpoint;

// Configuration des options de la requête
$options = [
    'http' => [
        'header' => "X-Auth-Token: $token",
        'method' => 'GET'
    ]
];

// Création du contexte de la requête
$context = stream_context_create($options);

// Exécution de la requête et récupération de la réponse
$response = file_get_contents($url, false, $context);

// Vérification si la requête a réussi
if ($response === false) {
    die('Erreur lors de la requête vers l\'API.');
}

// Traitement de la réponse
$data = json_decode($response, true);

// Récupération des informations sur les équipes
$standings = $data['standings'][0]['table'];

// Fonction de comparaison pour le tri des équipes par points
usort($standings, function ($a, $b) {
    return $b['points'] - $a['points'];
});

// Affichage des informations sur les équipes dans un tableau
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th>Position</th>';
echo '<th>Logo</th>';
echo '<th>Équipe</th>';
echo '<th>Matchs Joués</th>';
echo '<th>Points</th>';
echo '<th>Victoires</th>';
echo '<th>Nuls</th>';
echo '<th>Défaites</th>';
echo '<th>Buts marqués</th>';
echo '<th>Buts encaissés</th>';
echo '<th>Différence de buts</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($standings as $index => $team) {
    $position = $index + 1;
    $logo = $team['team']['crestUrl'];
    $name = $team['team']['name'];
    $playedGames = $team['playedGames'];
    $points = $team['points'];
    $wins = $team['won'];
    $draws = $team['draw'];
    $losses = $team['lost'];
    $goalsFor = $team['goalsFor'];
    $goalsAgainst = $team['goalsAgainst'];
    $goalDifference = $team['goalDifference'];

    $rowClass = '';
    if ($position === 1) {
        $rowClass = 'champion';
    } elseif ($position <= 4) {
        $rowClass = 'champions-league';
    } elseif ($position <= 6) {
        $rowClass = 'europa-league';
    } elseif ($position <= 17) {
        $rowClass = 'europa-conference';
    } else {
        $rowClass = 'relegation';
    }

    echo '<tr class="' . $rowClass . '">';
    echo '<td>' . $position . '</td>';
    echo '<td><img src="' . $logo . '" alt="Logo" height="50"></td>';
    echo '<td>' . $name . '</td>';
    echo '<td>' . $playedGames . '</td>';
    echo '<td>' . $points . '</td>';
    echo '<td>' . $wins . '</td>';
    echo '<td>' . $draws . '</td>';
    echo '<td>' . $losses . '</td>';
    echo '<td>' . $goalsFor . '</td>';
    echo '<td>' . $goalsAgainst . '</td>';
    echo '<td>' . $goalDifference . '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 8px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tr.champion {
        background-color: #4287f5;
        color: #fff;
    }

    tr.champions-league {
        background-color: #a0d468;
    }

    tr.europa-league {
        background-color: #5cb85c;
    }

    tr.europa-conference {
        background-color: #f9a752;
    }

    tr.relegation {
        background-color: #ff4d4d;
        color: #fff;
    }
</style>
