<?php
session_start();
set_time_limit(60); // Aumenta o limite de execução para 60 segundos
require_once 'functions.php';

if (!isset($_SESSION['deck_id'])) {
    $_SESSION['deck_id'] = createDeck();
}

function initializeGame() {
    $_SESSION['players'] = [
        ['name' => 'Player 1', 'hand' => [], 'score' => 0, 'stopped' => false],
        ['name' => 'Player 2', 'hand' => [], 'score' => 0, 'stopped' => false]
    ];
    $_SESSION['current_player'] = 0;
    $_SESSION['game_over'] = false;
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = [];
    }
}

if (isset($_POST['reset_game'])) {
    initializeGame();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['reset_history'])) {
    $_SESSION['history'] = [];
}

if (empty($_SESSION['players'])) {
    initializeGame();
}

if (isset($_POST['draw_card'])) {
    $currentPlayer = &$_SESSION['players'][$_SESSION['current_player']];

    if (!$currentPlayer['stopped']) {
        $cards = drawCards(1);

        if (!empty($cards)) { // Verifica se há uma carta válida
            $card = $cards[0];
            $currentPlayer['hand'][] = $card;
            $currentPlayer['score'] += getCardValue($card['value']);

            if ($currentPlayer['score'] > 21) {
                $currentPlayer['stopped'] = true;
                $currentPlayer['message'] = "Você estourou com {$currentPlayer['score']}!";
            }
        } else {
            $currentPlayer['message'] = "Erro ao tentar comprar carta. Tente novamente.";
        }
    }

    // Verifica se ambos os jogadores pararam
    if ($_SESSION['players'][0]['stopped'] && $_SESSION['players'][1]['stopped']) {
        $_SESSION['game_over'] = true;
    }
}

if (isset($_POST['stop'])) {
    $currentPlayer = &$_SESSION['players'][$_SESSION['current_player']];
    $currentPlayer['stopped'] = true;
    $currentPlayer['message'] = "Você parou com {$currentPlayer['score']}.";

    // Agora só muda a vez se o outro jogador não tiver parado
    if (!$_SESSION['players'][($_SESSION['current_player'] + 1) % 2]['stopped']) {
        $_SESSION['current_player'] = ($_SESSION['current_player'] + 1) % 2;
    }

    // Verifica se ambos os jogadores pararam
    if ($_SESSION['players'][0]['stopped'] && $_SESSION['players'][1]['stopped']) {
        $_SESSION['game_over'] = true;
    }
}

$winners = [];
if ($_SESSION['game_over']) {
    $maxScore = -1;
    foreach ($_SESSION['players'] as $player) {
        if ($player['score'] <= 21 && $player['score'] > $maxScore) {
            $maxScore = $player['score'];
            $winners = [$player['name']];
        } elseif ($player['score'] === $maxScore) {
            $winners[] = $player['name'];
        }
    }

    if (!empty($winners)) {
        $_SESSION['history'][] = implode(', ', $winners);
        if (count($_SESSION['history']) > 5) {
            array_shift($_SESSION['history']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Jogo 21</title>
</head>
<body>
    <div class="game-container">
        <h1>Jogo 21 - Blackjack</h1>

        <div class="players-container">
            <!-- Moldura do Player 1 -->
            <div class="player-frame">
                <h3>
                    <?php echo htmlspecialchars($_SESSION['players'][0]['name']); ?>
                    <?php if ($_SESSION['current_player'] === 0 && !$_SESSION['game_over']): ?>
                        <span class="turn-indicator"> - Sua vez!</span>
                    <?php endif; ?>
                </h3>
                <div>
                    <?php foreach ($_SESSION['players'][0]['hand'] as $card): ?>
                        <img class="card" src="<?php echo $card['image']; ?>" alt="<?php echo $card['code']; ?>">
                    <?php endforeach; ?>
                </div>
                <p>Pontuação: <?php echo $_SESSION['players'][0]['score']; ?></p>
                <?php if (isset($_SESSION['players'][0]['message'])): ?>
                    <p><?php echo $_SESSION['players'][0]['message']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Moldura do Player 2 -->
            <div class="player-frame">
                <h3>
                    <?php echo htmlspecialchars($_SESSION['players'][1]['name']); ?>
                    <?php if ($_SESSION['current_player'] === 1 && !$_SESSION['game_over']): ?>
                        <span class="turn-indicator"> - Sua vez!</span>
                    <?php endif; ?>
                </h3>
                <div>
                    <?php foreach ($_SESSION['players'][1]['hand'] as $card): ?>
                        <img class="card" src="<?php echo $card['image']; ?>" alt="<?php echo $card['code']; ?>">
                    <?php endforeach; ?>
                </div>
                <p>Pontuação: <?php echo $_SESSION['players'][1]['score']; ?></p>
                <?php if (isset($_SESSION['players'][1]['message'])): ?>
                    <p><?php echo $_SESSION['players'][1]['message']; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mostrar vencedor -->
        <?php if ($_SESSION['game_over']): ?>
            <div class="winner-announcement">
                <?php if (!empty($winners)): ?>
                    <h2>Vencedor(es): <?php echo implode(', ', $winners); ?></h2>
                <?php else: ?>
                    <h2>Ninguém venceu. Todos estouraram!</h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Histórico de Vencedores -->
        <div class="history">
            <h2>Histórico dos Últimos Jogos</h2>
            <ul>
                <?php foreach ($_SESSION['history'] as $historicalWinner): ?>
                    <li><?php echo htmlspecialchars($historicalWinner); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Botões de ação do jogo -->
        <form method="post">
            <?php if (!$_SESSION['game_over']): ?>
                <button type="submit" name="draw_card" class="reset-button action-button-alt">Comprar Carta</button>
                <button type="submit" name="stop" class="reset-button action-button-alt">Parar</button>
            <?php endif; ?>
            <button type="submit" name="reset_game" class="reset-button">Reiniciar Jogo</button>
            <button type="submit" name="reset_history" class="reset-button">Zerar Histórico</button>
        </form>
    </div>
</body>
</html>
<!--perfeito até aqui