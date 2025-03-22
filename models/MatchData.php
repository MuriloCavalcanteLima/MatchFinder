<?php
    class MatchData {
        public function getData($url) {
            $this->url = $url;

            $data = $this->request_data($this->url);

            return json_decode($data, true);
        }

        public function getCompetitions() {
            $data = $this->request_data('https://api.football-data.org/v4/competitions');
            return json_decode($data, true);
        }

        public function getTeams($competition) {
            $data = $this->request_data("https://api.football-data.org/v4/competitions/{$competition}/teams");
            return json_decode($data, true);
        }

        public function getAllTeams() {
            $data = $this->request_data("https://api.football-data.org/v4/teams");
            return json_decode($data, true);
        }

        public function getCompetitionIdBySlug($slug) {
            $competitions = $this->getCompetitions();
            foreach($competitions['competitions'] as $competition) {
                if ($competition['code'] == $slug) {
                    return $competition['id'];
                }
            }
            return null;
        }

        public function getCompetitionSlugs() {
            $slugs = [];
            $competitions = $this->getCompetitions();
            foreach($competitions['competitions'] as $competition) {
                $slugs[] = $competition['code'];
            }
            return $slugs;
        }

        private function request_data($url) {
            $reqPrefs['http']['method'] = 'GET';
            $reqPrefs['http']['header'] = "X-Auth-Token: $this->apiToken";
            $stream_context = stream_context_create($reqPrefs);
            $response = file_get_contents($url, false, $stream_context);
            return $response;
        }
    }
?>