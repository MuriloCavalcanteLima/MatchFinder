<?php
    include_once __DIR__ . '/../../models/MatchData.php';

    class MatchesApiController {

        public function index(){
            try {
                $data = $this->getMatchesByCompetition('CL');
            } catch (Exception $e) {
                $data['error'] = 'Ocorreu um erro ao buscar os dados: ' . $e->getMessage();
            } finally {
                return $data;
            }
        }

        public function search($competition, $team){
            try {
                if (empty($competition) && empty($team)) {
                    $data = $this->index();
                }elseif(empty($team)){ 
                    $data = $this->getMatchesByCompetition($competition);
                }elseif(empty($competition)){
                    $data = $this->getMatchesByTeam($team);
                }else{
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
            $data['scheduled'] = $matchData->getData("https://api.football-data.org/v4/competitions/{$code}/matches?status=SCHEDULED");
            $data['finished'] = $matchData->getData("https://api.football-data.org/v4/competitions/{$code}/matches?status=FINISHED");
            return $data;
        }

        private function getMatchesByTeam($team){
            $matchData = new MatchData();
            $team = urlencode(trim(preg_replace('/\s+/', '', $team)));
            $data['scheduled'] = $matchData->getData("https://api.football-data.org/v4/teams/{$team}/matches?status=SCHEDULED");
            $data['finished'] = $matchData->getData("https://api.football-data.org/v4/teams/{$team}/matches?status=FINISHED");
            return json_encode($data);
        }


        private function getMatchesByCompetitionAndTeam($competition, $team){
            $matchData = new MatchData();
            $competitionId = $matchData->getCompetitionIdBySlug($competition);
            $competition = urlencode(trim(preg_replace('/\s+/', '', $competitionId)));
            $team = urlencode(trim(preg_replace('/\s+/', '', $team)));
            $data['scheduled'] = $matchData->getData("https://api.football-data.org/v4/teams/{$team}/matches?competitions={$competition}&status=SCHEDULED");
            $data['finished'] = $matchData->getData("https://api.football-data.org/v4/teams/{$team}/matches?competitions={$competition}&status=FINISHED");
            return json_encode($data);
        }
    }
?>