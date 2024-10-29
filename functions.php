<?php

// Função para criar um baralho
function createDeck() {
    $url = "https://deckofcardsapi.com/api/deck/new/shuffle/?deck_count=1";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Verifica se a API retornou o deck_id corretamente
    return $data['deck_id'] ?? null;
}

function drawCards($count) {
    if (!isset($_SESSION['deck_id'])) {
        $_SESSION['deck_id'] = createDeck();
    }

    $deck_id = $_SESSION['deck_id'];
    $url = "https://deckofcardsapi.com/api/deck/$deck_id/draw/?count=$count";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    // Verifica se não há mais cartas e recria o baralho
    if (isset($data['error']) && $data['error'] === "Not enough cards remaining to draw $count additional") {
        // Cria um novo baralho e tenta comprar as cartas novamente
        $_SESSION['deck_id'] = createDeck();
        $deck_id = $_SESSION['deck_id']; // Atualiza o deck_id
        $url = "https://deckofcardsapi.com/api/deck/$deck_id/draw/?count=$count";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
    }

    return $data['cards'] ?? []; // Retorna as cartas, se existirem
}

// Função para calcular o valor da carta
function getCardValue($cardValue) {
    switch ($cardValue) {
        case 'KING':
            return 13;
        case 'QUEEN':
            return 12;
        case 'JACK':
            return 11;
        case 'ACE':
            return 1; // Ás é considerado como 1
        default:
            return (int)$cardValue; // Para cartas de 2 a 10
    }
}
//perfeito até aqui