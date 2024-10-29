# Jogo 21 - Blackjack 🃏

Este projeto é uma implementação do jogo de **21 (Blackjack)** em PHP, desenvolvido com a **Deck of Cards API** para manipulação de cartas. Ele oferece uma experiência divertida e interativa com estilo de cassino!

## 📋 Descrição

O jogo 21 permite que dois jogadores disputem pela pontuação mais próxima de 21 sem ultrapassar o limite. Cada jogador possui ações alternadas para **comprar cartas** ou **parar**. Ao final da rodada, o jogador com a pontuação mais alta (sem ultrapassar 21) é declarado vencedor.

### 🎲 Regras Básicas
- Cartas possuem valores específicos: 
  - **2 a 10** conforme o valor numérico.
  - **J (Valete): 11**, **Q (Rainha): 12**, **K (Rei): 13**, **A (Ás): 1**.
- Jogadores se alternam para alcançar uma pontuação próxima de **21**.
- Exceder 21 pontos resulta em perda automática da rodada.

🕹️ Como Jogar
1. Clone o repositório:
git clone https://github.com/sscastilho/deck-of-cards-api-DIO-PHP-SSC.git

2. Navegue até o diretório do projeto e abra no VSCode ou editor de sua preferência.
3. Inicie o servidor PHP local:
php -S localhost:8000

4. No navegador, acesse http://localhost:8000 para jogar.

## 🎨 Estilização e Funcionalidades

- **Interface com Temática de Cassino**: imagem de fundo temática, botões em laranja, animações discretas e layout centralizado.
- **Histórico de Vencedores**: exibe os vencedores das últimas rodadas e mantém um ranking cumulativo.
- **Área de Licença**: em laranja translúcido, localizada no rodapé.

## 🚀 Tecnologias Utilizadas

- **PHP** para backend
- **Deck of Cards API** para manipulação de cartas
- **CSS** para estilização
- **Sessões PHP** para gerenciar o estado do jogo

📝 Licença
- Este projeto está licenciado sob a Licença MIT, com a seguinte adição:
- Nota: A cópia, modificação ou venda deste software sem a autorização prévia do autor não é permitida.
- Desenvolvido por Sullivan Santos Castilho

## 📂 Estrutura do Repositório

```plaintext
├── index.php               # Arquivo principal do jogo
├── functions.php           # Lógica de manipulação do baralho e do jogo
├── styles/                 # Pasta de arquivos CSS para estilização
└── README.md               # Documentação do projeto
