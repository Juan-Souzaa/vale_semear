<?php

return [
    'dashboard' => [
        'title' => 'Ajuda: Dashboard',
        'content' => '
            <p>O dashboard é a página principal do sistema, onde você pode visualizar:</p>
            <ul>
                <li><strong>Métricas gerais:</strong> Resumo de atividades, obrigações e reuniões</li>
                <li><strong>Atividades recentes:</strong> Últimas atividades cadastradas no sistema</li>
                <li><strong>Obrigações urgentes:</strong> Obrigações com prazo próximo ou vencidas</li>
                <li><strong>Reuniões próximas:</strong> Próximas reuniões agendadas</li>
            </ul>
            <p>Use os cards para navegar rapidamente para as seções específicas.</p>
        ',
    ],

    'roles.index' => [
        'title' => 'Ajuda: Gerenciamento de Roles',
        'content' => '
            <p>As <strong>Roles</strong> (papeis) são grupos de permissões que podem ser atribuídos aos usuários.</p>
            <h6>Como funciona:</h6>
            <ul>
                <li><strong>Criar Role:</strong> Clique em "Nova Role" e defina quais permissões ela terá</li>
                <li><strong>Editar Role:</strong> Clique no ícone de editar para modificar permissões de uma role existente</li>
                <li><strong>Atribuir a Usuários:</strong> Na seção "Gerenciar Usuários", clique em "Gerenciar Permissões" do usuário e selecione as roles desejadas</li>
            </ul>
            <p><strong>Nota:</strong> A role "Super Admin" não pode ser editada ou excluída, pois possui todas as permissões do sistema.</p>
        ',
    ],

    'roles.create' => [
        'title' => 'Ajuda: Criar Nova Role',
        'content' => '
            <p>Para criar uma nova role:</p>
            <ol>
                <li>Digite um nome descritivo para a role (ex: "Gerente de Projetos")</li>
                <li>Selecione as permissões que esta role terá</li>
                <li>Clique em "Salvar"</li>
            </ol>
            <p><strong>Dica:</strong> Você pode selecionar permissões individuais ou marcar todo um grupo de permissões de uma vez.</p>
        ',
    ],

    'roles.edit' => [
        'title' => 'Ajuda: Editar Role',
        'content' => '
            <p>Na edição de role você pode:</p>
            <ul>
                <li>Alterar o nome da role</li>
                <li>Adicionar ou remover permissões</li>
            </ul>
            <p><strong>Importante:</strong> As alterações afetarão todos os usuários que possuem esta role.</p>
        ',
    ],

    'permissions.index' => [
        'title' => 'Ajuda: Permissões do Sistema',
        'content' => '
            <p>Esta página lista todas as permissões disponíveis no sistema, organizadas por módulo.</p>
            <p><strong>Nota:</strong> As permissões são criadas automaticamente via seeder e não podem ser editadas ou excluídas pelo painel administrativo.</p>
            <p>Para atribuir permissões aos usuários, use o sistema de <strong>Roles</strong> ou atribua permissões diretas na página de gerenciamento de usuários.</p>
        ',
    ],

    'users.roles' => [
        'title' => 'Ajuda: Gerenciar Permissões do Usuário',
        'content' => '
            <p>Nesta página você pode gerenciar as permissões de um usuário específico de duas formas:</p>
            <h6>1. Atribuir Roles:</h6>
            <p>Marque as roles que o usuário deve ter. As roles são grupos de permissões pré-definidos.</p>
            <h6>2. Permissões Diretas:</h6>
            <p>Atribua permissões individuais diretamente ao usuário, além das que ele já possui através das roles.</p>
            <p><strong>Dica:</strong> O usuário terá acesso a todas as permissões de suas roles + permissões diretas atribuídas.</p>
        ',
    ],

    'atividades.index' => [
        'title' => 'Ajuda: Atividades',
        'content' => '
            <p>As atividades representam eventos, mutirões, workshops e outras ações planejadas pela associação.</p>
            <p>Você pode:</p>
            <ul>
                <li>Criar novas atividades</li>
                <li>Visualizar detalhes de atividades existentes</li>
                <li>Editar ou excluir atividades</li>
                <li>Filtrar por tipo, status ou período</li>
            </ul>
        ',
    ],

    'atividades.create' => [
        'title' => 'Ajuda: Criar Nova Atividade',
        'content' => '
            <p>Preencha os campos do formulário:</p>
            <ul>
                <li><strong>Título:</strong> Nome da atividade</li>
                <li><strong>Descrição:</strong> Detalhes sobre a atividade</li>
                <li><strong>Tipo:</strong> Mutirão, Workshop, Evento, etc.</li>
                <li><strong>Datas:</strong> Data de início e fim</li>
                <li><strong>Status:</strong> Planejada, Em andamento, Concluída</li>
                <li><strong>Orçamento:</strong> Valor estimado (opcional)</li>
            </ul>
        ',
    ],

    'obrigacoes.index' => [
        'title' => 'Ajuda: Obrigações',
        'content' => '
            <p>As obrigações são tarefas legais, administrativas ou financeiras que precisam ser cumpridas dentro de prazos específicos.</p>
            <p>O sistema alerta sobre:</p>
            <ul>
                <li>Obrigações vencidas (em vermelho)</li>
                <li>Obrigações com prazo próximo (em amarelo)</li>
                <li>Obrigações em dia (em verde)</li>
            </ul>
            <p>Use os filtros para encontrar obrigações específicas por tipo, prioridade ou status.</p>
        ',
    ],

    'obrigacoes.create' => [
        'title' => 'Ajuda: Criar Nova Obrigação',
        'content' => '
            <p>Preencha os campos obrigatórios:</p>
            <ul>
                <li><strong>Título:</strong> Nome da obrigação</li>
                <li><strong>Tipo:</strong> Legal, Administrativa, Financeira</li>
                <li><strong>Data de Vencimento:</strong> Prazo final para cumprimento</li>
                <li><strong>Prioridade:</strong> Urgente, Alta, Média, Baixa</li>
                <li><strong>Responsável:</strong> Usuário responsável pela obrigação</li>
            </ul>
            <p><strong>Dica:</strong> Configure a data de lembrete para receber notificações antes do vencimento.</p>
        ',
    ],

    'reunioes.index' => [
        'title' => 'Ajuda: Reuniões',
        'content' => '
            <p>Gerencie todas as reuniões da associação:</p>
            <ul>
                <li>Visualize reuniões agendadas, em andamento e concluídas</li>
                <li>Crie novas reuniões e convide participantes</li>
                <li>Configure lembretes automáticos</li>
                <li>Gere atas e registre decisões tomadas</li>
            </ul>
            <p>Use os filtros para encontrar reuniões por tipo, status ou período.</p>
        ',
    ],

    'reunioes.create' => [
        'title' => 'Ajuda: Criar Nova Reunião',
        'content' => '
            <p>Para criar uma reunião:</p>
            <ol>
                <li>Preencha título, descrição e tipo da reunião</li>
                <li>Defina data, hora e local</li>
                <li>Adicione participantes</li>
                <li>Configure lembretes (opcional)</li>
            </ol>
            <p><strong>Dica:</strong> Após criar a reunião, você pode adicionar mais participantes e configurar lembretes na página de detalhes.</p>
        ',
    ],

    'atas.create' => [
        'title' => 'Ajuda: Criar Ata',
        'content' => '
            <p>As atas registram o que foi discutido e decidido em uma reunião.</p>
            <p>Preencha:</p>
            <ul>
                <li><strong>Número da Ata:</strong> Identificação única (ex: ATA-001/2025)</li>
                <li><strong>Conteúdo:</strong> Registro completo da reunião</li>
            </ul>
            <p>Após criar, você pode aprovar a ata e vincular decisões tomadas durante a reunião.</p>
        ',
    ],

    'decisoes.create' => [
        'title' => 'Ajuda: Criar Decisão',
        'content' => '
            <p>As decisões são ações acordadas durante uma reunião que precisam ser executadas.</p>
            <p>Para cada decisão, defina:</p>
            <ul>
                <li><strong>Título:</strong> Nome da decisão</li>
                <li><strong>Descrição:</strong> Detalhes do que foi decidido</li>
                <li><strong>Responsável:</strong> Quem ficará responsável pela execução</li>
                <li><strong>Prazo:</strong> Data limite para conclusão</li>
                <li><strong>Status:</strong> Pendente, Em andamento, Concluída</li>
            </ul>
        ',
    ],

    'relatorios.index' => [
        'title' => 'Ajuda: Relatórios',
        'content' => '
            <p>Gere relatórios detalhados sobre:</p>
            <ul>
                <li><strong>Atividades:</strong> Relatório de todas as atividades em um período</li>
                <li><strong>Reuniões:</strong> Relatório de reuniões realizadas</li>
                <li><strong>Financeiro:</strong> Relatório financeiro com orçamentos e gastos</li>
            </ul>
            <p>Você pode exportar os relatórios em formato <strong>CSV</strong> ou <strong>PDF</strong>.</p>
            <p>Use os filtros de data para definir o período desejado.</p>
        ',
    ],
];

