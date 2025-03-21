<?php

    include_once __DIR__ . '/../models/MatchData.php';
    include_once __DIR__ . '/api/matches_api_controller.php';

    class MatchesController {

        private $team;
        private $competition;

        public function __construct(){
            $this->team = isset($_GET['team']) ? trim($_GET['team']) : null;
            $this->competition = isset($_GET['competition']) ? trim($_GET['competition']) : null;
        }
        
        public function index(){
            $apiController = new MatchesApiController();
            try {
                    $comp = $this->competition;
                    $team = $this->team;
                    $data = $apiController->search($comp, $team);
                    $error = $data['error'] ?? null;

                    if ($error) {
                        $_SESSION['flash_message'] = [
                            'type' => 'error',
                            'message' => $error
                        ];
                        header('Location: /index.php');
                        exit;
                    }
            
                    require_once __DIR__ . '/../views/match_data.php';

            } catch (Exception $e) {
                                $_SESSION['flash_message'] = [
                                    'type' => 'error',
                                    'message' => 'Ocorreu um erro ao buscar os dados: ' . $e->getMessage()
                                ];
                                header('Location: /index.php');
                                exit;
            }
        }
    }
?>