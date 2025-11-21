<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Atividade;
use App\Models\Obrigacao;
use App\Models\Reuniao;
use App\Models\ParticipanteReuniao;
use App\Models\Ata;
use App\Models\Decisao;
use App\Models\LembreteReuniao;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar admin já criado pelo AdminSeeder (não criar duplicado)
        $admin = User::where('email', 'admin@semear.com')->first();
        
        if (!$admin) {
            $this->command->warn('Admin não encontrado. Execute AdminSeeder primeiro!');
            return;
        }

        // Criar usuários de teste
        $joao = User::firstOrCreate(
            ['email' => 'joao@semear.com'],
            [
                'name' => 'João Silva',
                'password' => Hash::make('12345678'),
            ]
        );
        
        $maria = User::firstOrCreate(
            ['email' => 'maria@semear.com'],
            [
                'name' => 'Maria Santos',
                'password' => Hash::make('12345678'),
            ]
        );
        
        $pedro = User::firstOrCreate(
            ['email' => 'pedro@semear.com'],
            [
                'name' => 'Pedro Oliveira',
                'password' => Hash::make('12345678'),
            ]
        );
        
        $ana = User::firstOrCreate(
            ['email' => 'ana@semear.com'],
            [
                'name' => 'Ana Costa',
                'password' => Hash::make('12345678'),
            ]
        );

        // Atribuir role "Usuário" aos usuários de teste (se não tiverem role)
        $roleUsuario = \Spatie\Permission\Models\Role::where('name', 'Usuário')->first();
        
        if ($roleUsuario) {
            foreach ([$joao, $maria, $pedro, $ana] as $user) {
                if (!$user->hasAnyRole()) {
                    $user->assignRole($roleUsuario);
                }
            }
        }

        $users = [$joao, $maria, $pedro, $ana];
        $allUsers = collect($users)->push($admin);

        // Criar Atividades
        $atividades = [
            [
                'titulo' => 'Mutirão de Limpeza do Parque',
                'descricao' => 'Organização de mutirão para limpeza e manutenção do parque comunitário',
                'tipo' => 'mutirao',
                'data_inicio' => now()->subDays(30),
                'data_fim' => now()->subDays(28),
                'status' => 'concluida',
                'local' => 'Parque Central',
                'responsavel_id' => $admin->id,
                'orcamento' => 500.00,
                'observacoes' => 'Atividade realizada com sucesso, 25 voluntários participaram',
            ],
            [
                'titulo' => 'Workshop de Compostagem',
                'descricao' => 'Workshop sobre técnicas de compostagem doméstica',
                'tipo' => 'workshop',
                'data_inicio' => now()->subDays(15),
                'data_fim' => now()->subDays(15),
                'status' => 'concluida',
                'local' => 'Sede da Associação',
                'responsavel_id' => $joao->id,
                'orcamento' => 300.00,
            ],
            [
                'titulo' => 'Melhoria da Iluminação Externa',
                'descricao' => 'Instalação de novas lâmpadas LED na área externa',
                'tipo' => 'melhoria',
                'data_inicio' => now()->subDays(10),
                'data_fim' => now()->subDays(5),
                'status' => 'concluida',
                'local' => 'Área Externa',
                'responsavel_id' => $maria->id,
                'orcamento' => 1200.00,
            ],
            [
                'titulo' => 'Evento de Integração Comunitária',
                'descricao' => 'Evento para integração dos novos moradores',
                'tipo' => 'evento',
                'data_inicio' => now()->addDays(5),
                'data_fim' => now()->addDays(5),
                'status' => 'em_andamento',
                'local' => 'Salão de Festas',
                'responsavel_id' => $pedro->id,
                'orcamento' => 800.00,
            ],
            [
                'titulo' => 'Treinamento de Primeiros Socorros',
                'descricao' => 'Curso básico de primeiros socorros para a comunidade',
                'tipo' => 'treinamento',
                'data_inicio' => now()->addDays(10),
                'data_fim' => now()->addDays(12),
                'status' => 'planejada',
                'local' => 'Sede da Associação',
                'responsavel_id' => $admin->id,
                'orcamento' => 600.00,
            ],
            [
                'titulo' => 'Reforma do Playground',
                'descricao' => 'Reforma completa do playground infantil',
                'tipo' => 'melhoria',
                'data_inicio' => now()->addDays(20),
                'data_fim' => now()->addDays(25),
                'status' => 'planejada',
                'local' => 'Área de Lazer',
                'responsavel_id' => $ana->id,
                'orcamento' => 3500.00,
            ],
            [
                'titulo' => 'Mutirão de Pintura',
                'descricao' => 'Pintura das áreas comuns do condomínio',
                'tipo' => 'mutirao',
                'data_inicio' => now()->addDays(3),
                'data_fim' => now()->addDays(3),
                'status' => 'em_andamento',
                'local' => 'Áreas Comuns',
                'responsavel_id' => $joao->id,
                'orcamento' => 1500.00,
            ],
        ];

        foreach ($atividades as $atividade) {
            Atividade::create($atividade);
        }

        // Criar Obrigações
        $obrigacoes = [
            [
                'titulo' => 'Declaração de Imposto de Renda',
                'descricao' => 'Entrega da declaração de IR da associação',
                'tipo' => 'legal',
                'data_vencimento' => now()->subDays(5),
                'data_lembrete' => now()->subDays(10),
                'status' => 'vencida',
                'prioridade' => 'urgente',
                'responsavel_id' => $admin->id,
            ],
            [
                'titulo' => 'Renovação de Alvará',
                'descricao' => 'Renovação do alvará de funcionamento',
                'tipo' => 'legal',
                'data_vencimento' => now()->addDays(15),
                'data_lembrete' => now()->addDays(10),
                'status' => 'pendente',
                'prioridade' => 'alta',
                'responsavel_id' => $admin->id,
            ],
            [
                'titulo' => 'Prestação de Contas Mensal',
                'descricao' => 'Elaboração e publicação da prestação de contas do mês',
                'tipo' => 'administrativa',
                'data_vencimento' => now()->addDays(2),
                'data_lembrete' => now()->subDays(1),
                'status' => 'em_andamento',
                'prioridade' => 'alta',
                'responsavel_id' => $maria->id,
            ],
            [
                'titulo' => 'Pagamento de Taxas',
                'descricao' => 'Pagamento de taxas municipais',
                'tipo' => 'financeira',
                'data_vencimento' => now()->addDays(7),
                'data_lembrete' => now()->addDays(5),
                'status' => 'pendente',
                'prioridade' => 'media',
                'responsavel_id' => $maria->id,
            ],
            [
                'titulo' => 'Relatório Anual de Atividades',
                'descricao' => 'Elaboração do relatório anual para apresentação na assembleia',
                'tipo' => 'administrativa',
                'data_vencimento' => now()->addDays(30),
                'data_lembrete' => now()->addDays(25),
                'status' => 'pendente',
                'prioridade' => 'media',
                'responsavel_id' => $admin->id,
            ],
            [
                'titulo' => 'Reunião com Prefeitura',
                'descricao' => 'Agendamento e preparação para reunião com representantes da prefeitura',
                'tipo' => 'administrativa',
                'data_vencimento' => now()->addDays(1),
                'data_lembrete' => now()->subDays(2),
                'status' => 'pendente',
                'prioridade' => 'urgente',
                'responsavel_id' => $pedro->id,
            ],
        ];

        foreach ($obrigacoes as $obrigacao) {
            Obrigacao::create($obrigacao);
        }

        // Criar Reuniões
        $reunioes = [
            [
                'titulo' => 'Reunião de Diretoria - Novembro',
                'descricao' => 'Reunião mensal da diretoria para discussão de assuntos administrativos',
                'tipo' => 'diretoria',
                'data_hora' => now()->subDays(20)->setTime(19, 0),
                'local' => 'Sede da Associação',
                'status' => 'concluida',
                'organizador_id' => $admin->id,
            ],
            [
                'titulo' => 'Assembleia Geral Ordinária',
                'descricao' => 'Assembleia para aprovação de orçamento e prestação de contas',
                'tipo' => 'assembleia',
                'data_hora' => now()->subDays(10)->setTime(18, 30),
                'local' => 'Salão de Festas',
                'status' => 'concluida',
                'organizador_id' => $admin->id,
            ],
            [
                'titulo' => 'Reunião de Comissão de Obras',
                'descricao' => 'Discussão sobre as melhorias planejadas',
                'tipo' => 'comissao',
                'data_hora' => now()->addDays(2)->setTime(19, 0),
                'local' => 'Sede da Associação',
                'status' => 'agendada',
                'organizador_id' => $pedro->id,
            ],
            [
                'titulo' => 'Reunião de Diretoria - Dezembro',
                'descricao' => 'Reunião mensal da diretoria',
                'tipo' => 'diretoria',
                'data_hora' => now()->addDays(15)->setTime(19, 0),
                'local' => 'Sede da Associação',
                'status' => 'agendada',
                'organizador_id' => $admin->id,
            ],
            [
                'titulo' => 'Reunião Extraordinária',
                'descricao' => 'Discussão sobre questões urgentes',
                'tipo' => 'outro',
                'data_hora' => now()->addDays(1)->setTime(18, 0),
                'local' => 'Online',
                'status' => 'confirmada',
                'organizador_id' => $maria->id,
            ],
        ];

        $reunioesCriadas = [];
        foreach ($reunioes as $reuniao) {
            $reunioesCriadas[] = Reuniao::create($reuniao);
        }

        // Adicionar participantes às reuniões
        foreach ($reunioesCriadas as $index => $reuniao) {
            // Adicionar alguns participantes aleatórios
            $participantes = $allUsers->random(rand(2, 4));
            
            foreach ($participantes as $user) {
                $confirmado = $reuniao->status === 'concluida' || rand(0, 1);
                $presente = $reuniao->status === 'concluida' && $confirmado && rand(0, 1);
                
                ParticipanteReuniao::create([
                    'reuniao_id' => $reuniao->id,
                    'user_id' => $user->id,
                    'confirmado' => $confirmado,
                    'presente' => $presente,
                ]);
            }
        }

        // Criar Atas para reuniões concluídas
        $reunioesConcluidas = collect($reunioesCriadas)->filter(fn($r) => $r->status === 'concluida');
        
        foreach ($reunioesConcluidas as $index => $reuniao) {
            $ata = Ata::create([
                'reuniao_id' => $reuniao->id,
                'numero' => 'ATA-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '/' . now()->year,
                'data_aprovacao' => $reuniao->data_hora->addDays(2),
                'conteudo' => "ATA DA REUNIÃO\n\n" . 
                             "Data: " . $reuniao->data_hora->format('d/m/Y H:i') . "\n" .
                             "Local: " . $reuniao->local . "\n" .
                             "Tipo: " . ucfirst($reuniao->tipo) . "\n\n" .
                             "Pauta:\n" . $reuniao->descricao . "\n\n" .
                             "Participantes presentes: " . $reuniao->participantes()->where('presente', true)->count() . "\n\n" .
                             "Decisões tomadas durante a reunião foram registradas no sistema.",
                'aprovada' => $index === 0, // Primeira ata aprovada
                'criado_por_id' => $reuniao->organizador_id,
            ]);

            // Criar Decisões para as atas
            if ($index === 0) {
                Decisao::create([
                    'reuniao_id' => $reuniao->id,
                    'ata_id' => $ata->id,
                    'titulo' => 'Aprovação do Orçamento 2025',
                    'descricao' => 'Aprovação do orçamento anual para o exercício de 2025',
                    'status' => 'concluida',
                    'prazo' => now()->addDays(30),
                    'responsavel_id' => $admin->id,
                ]);

                Decisao::create([
                    'reuniao_id' => $reuniao->id,
                    'ata_id' => $ata->id,
                    'titulo' => 'Contratação de Empresa para Reforma',
                    'descricao' => 'Contratação de empresa especializada para reforma do playground',
                    'status' => 'em_andamento',
                    'prazo' => now()->addDays(45),
                    'responsavel_id' => $ana->id,
                ]);
            }

            if ($index === 1) {
                Decisao::create([
                    'reuniao_id' => $reuniao->id,
                    'ata_id' => $ata->id,
                    'titulo' => 'Implementação de Sistema de Notificações',
                    'descricao' => 'Implementação de sistema automatizado de notificações por e-mail',
                    'status' => 'pendente',
                    'prazo' => now()->addDays(60),
                    'responsavel_id' => $joao->id,
                ]);
            }
        }

        // Criar Lembretes para reuniões futuras
        $reunioesFuturas = collect($reunioesCriadas)->filter(fn($r) => in_array($r->status, ['agendada', 'confirmada']));
        
        foreach ($reunioesFuturas as $reuniao) {
            // Adicionar lembretes variados
            $tiposLembrete = ['1_dia', '3_dias', '1_semana'];
            $lembretesSelecionados = collect($tiposLembrete)->random(rand(1, 3));
            
            foreach ($lembretesSelecionados as $tipo) {
                LembreteReuniao::create([
                    'reuniao_id' => $reuniao->id,
                    'tipo' => $tipo,
                    'enviado' => false,
                ]);
            }
        }

        $this->command->info('✅ Seeder executado com sucesso!');
        $this->command->info('📊 Dados criados:');
        $this->command->info('   - ' . User::count() . ' usuários');
        $this->command->info('   - ' . Atividade::count() . ' atividades');
        $this->command->info('   - ' . Obrigacao::count() . ' obrigações');
        $this->command->info('   - ' . Reuniao::count() . ' reuniões');
        $this->command->info('   - ' . ParticipanteReuniao::count() . ' participantes');
        $this->command->info('   - ' . Ata::count() . ' atas');
        $this->command->info('   - ' . Decisao::count() . ' decisões');
        $this->command->info('   - ' . LembreteReuniao::count() . ' lembretes');
        $this->command->info('');
        $this->command->info('🔑 Credenciais de acesso:');
        $this->command->info('   Email: admin@semear.com');
        $this->command->info('   Senha: 12345678');
    }
}
