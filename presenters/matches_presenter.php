<?php
    class MatchesPresenter {

        function renderTeamInfo($teamName, $defaultMessage) {
            return !empty($teamName) ? htmlspecialchars((string)$teamName, ENT_QUOTES, 'UTF-8') : $defaultMessage;
        }

        function renderScore($score) {
            if (!empty($score)) {
                return "(".(string)$score['home']." X ".(string)$score['away'].")";
            }
            return '';
        }

        function renderDate($utcDate, $defaultMessage) {
            if (!empty($utcDate)) {
                $date = new DateTime((string)$utcDate);
                return $date->format('d/m/y H:i');
            }
            return $defaultMessage;
        }

        function renderVenue($venue, $defaultMessage) {
            return !empty($venue) ? htmlspecialchars((string)$venue, ENT_QUOTES, 'UTF-8') : $defaultMessage;
        }

        function renderCompetition($competition, $defaultMessage) {
            return !empty($competition) ? htmlspecialchars((string)$competition, ENT_QUOTES, 'UTF-8') : $defaultMessage;
        }

        function renderMatchInfo($item) {
            ob_start();
            ?>
            <tr>
                <td>
                    <?= $this->renderTeamInfo($item['homeTeam']['shortName'] ?? null, 'Time da casa não encontrado'); ?>
                    VS
                    <?= $this->renderTeamInfo($item['awayTeam']['shortName'] ?? null, 'Time visitante não encontrado'); ?>
                    <?= $this->renderScore($item['score']['fullTime'] ?? null); ?>
                </td>
                <td>
                    <?= $this->renderDate($item['utcDate'] ?? null, 'Horário não encontrado'); ?>
                </td>
                <td>
                    <?= $this->renderVenue($item['venue'] ?? $item['area']['name'], 'Estádio não encontrado'); ?>
                </td>
                <td>
                    <?= $this->renderCompetition($item['competition']['code'] ?? null, 'campeonato não encontrado'); ?>
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }
    }
?>