<?php require_once __DIR__ . '/../presenters/matches_presenter.php'; ?>
 
<?php include __DIR__ . '/../views/partials/header.php'; ?>
<?php include __DIR__ . '/../views/partials/forms/filter.php'; ?>

<body class="bg-light">
    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger "><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="container d-flex bg-light corner-radius-10 shadow-lg p-5 my-auto " style="max-height: 80vh;">
        <div class="table-responsive m-5 text-center" style=" overflow-y: auto; scroll-behavior: smooth;">
            <div class="text-center mb-4">
                <h1>Próximos Jogos</h1>
            </div>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Partida</th>
                        <th>Data</th>
                        <th>Estádio/Região</th>
                        <th>Campeonato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['scheduled'])): ?>
                        <tr>
                            <td colspan="4" class="text-center">Nenhum jogo agendado</td>
                        </tr>
                    <?php else : ?>
                        <?php $presenter = new MatchesPresenter(); ?>
                        <?php foreach ($data['scheduled']['matches'] as $item): ?>
                            <?= $presenter->renderMatchInfo($item); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Outra tabela para "Últimos Jogos" -->
        <div class="table-responsive m-5 text-center" style="max-height: 80vh; overflow-y: auto; scroll-behavior: smooth;">
            <div class="text-center mb-4">
                <h1>Últimos Jogos</h1>
            </div>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Partida</th>
                        <th>Data</th>
                        <th>Estádio/Região</th>
                        <th>Campeonato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['finished'])): ?>
                        <tr>
                            <td colspan="4" class="text-center">Nenhum jogo finalizado</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($data['finished']['matches'] as $item): ?>
                            <?= $presenter->renderMatchInfo($item); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>