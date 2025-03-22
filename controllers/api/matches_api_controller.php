<?php
    use Dotenv\Dotenv;

    class MatchesApiController {

        private $apiBaseUrl;
        private $apiKey;

        public function __construct() {
            $this->apiBaseUrl = 'https://api.football-data.org/v4';

            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
            $this->apiKey = $_ENV['API_KEY'];
        }

        public function index() {
            try {
                $data = $this->getMatchesByCompetition('CL');
            } catch (Exception $e) {
                $data['error'] = 'Ocorreu um erro ao buscar os dados: ' . $e->getMessage();
            } finally {
                return $data;
            }
        }

        public function search($competition, $team) {
            try {
                if (empty($competition) && empty($team)) {
                    $data = $this->index();
                } elseif (empty($team)) { 
                    $data = $this->getMatchesByCompetition($competition);
                } elseif (empty($competition)) {
                    $data = $this->getMatchesByTeam($team);
                } else {
                    $data = $this->getMatchesByCompetitionAndTeam($competition, $team);
                }
            } catch (Exception $e) {
                $data['error'] = 'Ocorreu um erro ao buscar os dados: ' . $e->getMessage();
            } finally {
                return $data;
            }
        }

        private function getMatchesByCompetition($competition){
            $code = urlencode(trim(preg_replace('/\s+/', '', $competition)));
            $data['scheduled'] = $this->getData("{$this->apiBaseUrl}/competitions/{$code}/matches?status=SCHEDULED");
            $data['finished'] = $this->getData("{$this->apiBaseUrl}/competitions/{$code}/matches?status=FINISHED");
            return $data;
        }

        private function getMatchesByTeam($team){
            $team = urlencode(trim(preg_replace('/\s+/', '', $team)));
            $data['scheduled'] = $this->getData("{$this->apiBaseUrl}/teams/{$team}/matches?status=SCHEDULED");
            $data['finished'] = $this->getData("{$this->apiBaseUrl}/teams/{$team}/matches?status=FINISHED");
            return json_encode($data);
        }


        private function getMatchesByCompetitionAndTeam($competition, $team){
            $matchData = new MatchData();
            $competitionId = $matchData->getCompetitionIdBySlug($competition);
            $comp = urlencode(trim(preg_replace('/\s+/', '', $competitionId)));
            $team = urlencode(trim(preg_replace('/\s+/', '', $team)));
            $data['scheduled'] = $this->getData("{$this->apiBaseUrl}/teams/{$team}/matches?competitions={$comp}&status=SCHEDULED");
            $data['finished'] = $this->getData("{$this->apiBaseUrl}/teams/{$team}/matches?competitions={$comp}&status=FINISHED");
            return json_encode($data);
        }

        public function getData($url) {
            $this->url = $url;

            $data = $this->makeRequest($this->url);

            return json_decode($data, true);
        }

        private function makeRequest($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "X-Auth-Token: {$this->apiKey}"
            ]);

            $response = curl_exec($ch);

            return $response;
        }
    }
?>