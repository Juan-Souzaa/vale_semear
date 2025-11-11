# Sistema SEMEAR

Sistema de Gestão de Atividades, Reuniões, Obrigações e Relatórios para Associações e Organizações.

## Descrição

O Sistema SEMEAR é uma aplicação web desenvolvida em Laravel que permite gerenciar:

- **Atividades**: Mutirões, melhorias, eventos, treinamentos e workshops
- **Reuniões**: Agendamento, gestão de participantes e lembretes
- **Obrigações**: Controle de obrigações legais e administrativas
- **Atas**: Registro e aprovação de atas de reuniões
- **Decisões**: Acompanhamento de decisões tomadas em reuniões
- **Relatórios**: Geração de relatórios em PDF e CSV

## Requisitos

- PHP >= 8.1
- Composer
- MySQL/MariaDB ou PostgreSQL

## Instalação

1. Clone o repositório:
```bash
git clone <url-do-repositorio>
cd Extensao
```

2. Instale as dependências do PHP:
```bash
composer install
```

3. Copie o arquivo de ambiente:
```bash
cp .env.example .env
```

4. Configure o arquivo `.env` com suas credenciais de banco de dados:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. Gere a chave da aplicação:
```bash
php artisan key:generate
```

6. Execute as migrations:
```bash
php artisan migrate
```

7. (Opcional) Execute o seeder para dados de teste:
```bash
php artisan db:seed --class=DatabaseTestSeeder
```

8. Inicie o servidor de desenvolvimento:
```bash
php artisan serve
```

Acesse a aplicação em: `http://localhost:8000`

## Estrutura do Banco de Dados

O sistema utiliza as seguintes tabelas principais:

- `users` - Usuários do sistema
- `atividades` - Atividades planejadas
- `reunioes` - Reuniões agendadas
- `obrigacoes` - Obrigações legais e administrativas
- `atas` - Atas de reuniões
- `decisoes` - Decisões tomadas em reuniões
- `tarefas` - Tarefas derivadas de decisões
- `participantes_reuniao` - Participantes de reuniões
- `lembretes_reuniao` - Lembretes configurados para reuniões
- `relatorios` - Relatórios gerados

## Tecnologias Utilizadas

- **Backend**: Laravel 10
- **Frontend**: Bootstrap 5, JavaScript
- **Banco de Dados**: MySQL/PostgreSQL
- **Bibliotecas**:
  - DomPDF (geração de PDFs)
  - League CSV (exportação CSV)
  - SweetAlert2 (alertas e confirmações)

## Funcionalidades Principais

- Autenticação de usuários
- Dashboard com notificações e métricas
- CRUD completo de atividades, reuniões e obrigações
- Sistema de lembretes para reuniões
- Geração de relatórios em PDF e CSV
- Interface responsiva para mobile e desktop
- Sistema de notificações em tempo real

