<?php
    class MatchesApiController {

        private $apiBaseUrl;
        private $apiKey;

        public function __construct() {
            $this->apiBaseUrl = 'https://api.football-data.org/v4';
            $this->apiKey = '56addeef3f3047658970676611b21143';
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
            $matchData = new MatchData();
            $code = urlencode(trim(preg_replace('/\s+/', '', $competition)));
            $data['scheduled'] = $this->getData("{$this->apiBaseUrl}/competitions/{$code}/matches?status=SCHEDULED");
            $data['finished'] = $this->getData("{$this->apiBaseUrl}/competitions/{$code}/matches?status=FINISHED");
            return $data;
        }

        private function getMatchesByTeam($team){
            $matchData = new MatchData();
            $team = urlencode(trim(preg_replace('/\s+/', '', $team)));
            $data['scheduled'] = $this->getData("{$this->apiBaseUrl}/teams/{$team}/matches?status=SCHEDULED");
            $data['finished'] = $this->getData("{$this->apiBaseUrl}/teams/{$team}/matches?status=FINISHED");
            return json_encode($data);
        }


        private function getMatchesByCompetitionAndTeam($competition, $team){
            $matchData = new MatchData();
            $competitionId = $this->getCompetitionIdBySlug($competition);
            $competition = urlencode(trim(preg_replace('/\s+/', '', $competitionId)));
            $team = urlencode(trim(preg_replace('/\s+/', '', $team)));
            $data['scheduled'] = $this->getData("{$this->apiBaseUrl}/teams/{$team}/matches?competitions={$competition}&status=SCHEDULED");
            $data['finished'] = $this->getData("{$this->apiBaseUrl}/teams/{$team}/matches?competitions={$competition}&status=FINISHED");
            return json_encode($data);
        }

        public function getData($url) {
            $this->url = $url;

            $data = $this->request_data($this->url);

            return json_decode($data, true);
        }

        private function request_data($url) {
            $reqPrefs['http']['method'] = 'GET';
            $reqPrefs['http']['header'] = "X-Auth-Token: $this->apiToken";
            $stream_context = stream_context_create($reqPrefs);
            $response = file_get_contents($url, false, $stream_context);
            return $response;
        }

        // private function makeRequest($endpoint) {
        //     $url = $this->apiBaseUrl . $endpoint;

        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //         "X-Auth-Token: {$this->apiKey}"
        //     ]);

        //     $response = curl_exec($ch);
        //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //     if (curl_errno($ch)) {
        //         $error = curl_error($ch);
        //         curl_close($ch);
        //         throw new Exception("Erro na requisição: {$error}");
        //     }

        //     curl_close($ch);

        //     if ($httpCode !== 200) {
        //         throw new Exception("Erro na API: Código HTTP {$httpCode}");
        //     }

        //     $decodedResponse = json_decode($response, true);

        //     if (json_last_error() !== JSON_ERROR_NONE) {
        //         throw new Exception('Erro ao decodificar a resposta da API.');
        //     }

        //     return $decodedResponse;
        // }
    }
?>