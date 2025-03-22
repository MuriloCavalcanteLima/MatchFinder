<?php
    require_once __DIR__ . '/vendor/autoload.php';
    session_start();

    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        echo '<div class="' . htmlspecialchars($flash['type']) . '">' . htmlspecialchars($flash['message']) . '</div>';
        unset($_SESSION['flash_message']);
    }

	require_once __DIR__ . '/controllers/matches_controller.php';

	$controller = new MatchesController();
	$controller->index();
?>
