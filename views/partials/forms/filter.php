<!-- filepath: /C:/xampp/htdocs/views/partials/forms/filter.php -->
<div class="container py-4">
    <form action="/index.php" method="GET" class="row g-3">
        <!-- Campo Competition -->
        <div class="col-md-6 text-center fs-2">
            <label for="competition" class="form-label">Competition:</label>
            <select name="competition" id="competition" class="form-select">
                <?php if (!empty($comp)) : ?>
                    <option value="<?= htmlspecialchars(trim($comp), ENT_QUOTES, 'UTF-8'); ?>">
                        <?= htmlspecialchars($comp, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php else : ?>
                    <option value=""> -- Select a competition -- </option>
                <?php endif; ?>
                <?php 
                    $matchData = new MatchData();
                    $competitions = $matchData->getCompetitions();
                    foreach ($competitions['competitions'] as $competition): ?>
                        <option value="<?= htmlspecialchars(trim($competition["code"]), ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars($competition["name"], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Campo Team -->
        <div class="col-md-6 text-center fs-2">
            <label for="team" class="form-label">Team:</label>
            <select name="team" id="team" class="form-select">
                <option value=""> -- Select a competition team -- </option>
                <?php 
                    $teams = !empty($comp) ? $matchData->getTeams($comp) : $matchData->getAllTeams();
                    foreach ($teams['teams'] as $team): ?>
                        <option value="<?= htmlspecialchars(trim($team["id"]), ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars($team["name"], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Botão de Enviar -->
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary px-4">Filter</button>
        </div>
    </form>
</div>