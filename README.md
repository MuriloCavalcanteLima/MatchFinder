## objetivo do projeto
O projeto consiste em um sistema simples em PHP desenvolvido localmente utilizando ambiente XAMPP, que consome uma API de futebol e lista na visualização informações sobre partidas, times e campeonatos.

## Requisitos:
- Permitir que o usuario:
	- Escolha um campeonato de futebol (pode ser o Campeonato Brasileiro ou outro campeonato).
	- Exiba os jogos programados para a próxima rodada.
  	- Mostre informações sobre os últimos resultados da competição (últimos jogos).
	- Permita pesquisar por um time específico e mostrar suas partidas programadas e últimos resultados.

## Dependências
  composer - dotenv

## Estrutura escolhida para desenvolvimento

### Controllers
  - MatchesController 
    - Recebe via GET informações do filtro (campeonato e/ou time), assinados como nulo na sua ausência (ex. no primeiro acesso

		- index
      - Instancia a controller que comunica com a API e realiza a chamada para a ação search
      - Assina informações que serão utilizadas no formulário, no flash message e na listagem.
        - $comp = $this->competition;
        - $team = $this->team;
        - $data = $apiController->search($comp, $team);
        - $error = $data['error'] ?? null;
			- Verifica a presença de $error (configuração do flash message)
			- Chama a visualização com o resultado obtido da API
			- O processo é englobado por um catch que dispara uma flashmessage com o erro, redirecionando para o index.

		
  - matchesAPIcontroller:
	  (segurança) A API escolhida para o desenvolvimento faz autenticação via apiToken, para isso o composer foi instalado para que o pacote dotEnv pudesse ser configurada a variavel de ambiente com o token.

	  - index
      - chama getMatchesByCompetition(), que monta a URL e chama o getData, que realiza a consulta com o pacote `curl`.

  	- search
       - monta a URL de acordo com a presença dos filtros de campeonato e/ou time
       - chama getData, que realiza a consulta com o pacote curl, retornando a resposta em json para a Matches controller.
### MODELO

  - MatchData
    - Consome a API para disponibilizar informações para a view e para a controller (formulário)


------- [ WIP ] ---------------
	- Verificação de requsitos
		Requisitos:
			Escolher um campeonato de futebol
				> filtro
			Exibir os jogos programados para a próxima rodada.
				> visualização
			Mostrar informações sobre os últimos resultados da competição.
				> visualização
			Pesquisar por um time específico e mostrar suas partidas programadas e últimos resultados.
				> filtro + visualização
			Tratamento de erros e segurança
				> APIcontroller, Session, .env e flashmessage
