## Inventário UTFPR

Inventário UTFPR é uma aplicação web que foi desenvolvida em Laravel e tem como finalidade auxiliar a comissão inventariante da UTFPR na coleta dos itens e geração de relatórios.

##Instalação
Para iniciar a instalação do sistema de coleta de inventário, devemos fazer o clone do repositório git:
```
git clone https://github.com/aulerwideck/inventario-utfpr.git
```
Posteriormente, devemos atualizar as dependências do projeto:
```
composer update
```
Feito isso, iremos configurar os acessos ao banco de dados, para isso devemos fazer uma cópia do arquivo .env.example com o nome .env:
```
cp .env.example .env
```
Dentro do arquivo .env deve ser inseridos as credenciais de acesso ao banco de dados.


## Upload de arquivo

O arquivo deve ser no formato csv no seguinte formato:

````
LOCAL; ;TOMBO;TOMBO_ANTIGO;NOME_RESPONSAVEL;DESCRIÇÃO_DO_ITEM
````
[Arquivo Exemplo](https://github.com/aulerwideck/inventario-utfpr/blob/master/public/uploads/example.csv)

Obs.: Entre o campo Local e Tombo, possui um espaço em branco.


## Licença
Este projeto está licenciado nos termos da licença [MIT](https://opensource.org/licenses/MIT).
