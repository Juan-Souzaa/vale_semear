@extends('layouts.app')

@section('title', $reuniao->titulo)

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="bi bi-people me-2"></i>{{ $reuniao->titulo }}
                </h1>
                <p class="text-muted">Detalhes da reunião</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('reunioes.edit', $reuniao) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
                <a href="{{ route('reunioes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Voltar
                </a>
            </div>
        </div>

        <!-- Informações da Reunião -->
        <div class="metric-card mb-4">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Status</label>
                    <div>
                        @php
                            $statusClass = match($reuniao->status) {
                                'concluida' => 'bg-success',
                                'confirmada' => 'bg-primary',
                                'em_andamento' => 'bg-warning',
                                'agendada' => 'bg-secondary',
                                'cancelada' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            $statusLabel = match($reuniao->status) {
                                'concluida' => 'Concluída',
                                'confirmada' => 'Confirmada',
                                'em_andamento' => 'Em Andamento',
                                'agendada' => 'Agendada',
                                'cancelada' => 'Cancelada',
                                default => ucfirst($reuniao->status)
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipo</label>
                    <p class="text-muted mb-0">{{ ucfirst($reuniao->tipo) }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Organizador</label>
                    <p class="text-muted mb-0">{{ $reuniao->organizador?->name ?? 'Não definido' }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Data e Hora</label>
                    <p class="text-muted mb-0">{{ $reuniao->data_hora->format('d/m/Y - H:i') }}</p>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Local</label>
                    <p class="text-muted mb-0">{{ $reuniao->local ?? 'Não definido' }}</p>
                </div>

                @if($reuniao->descricao)
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Descrição</label>
                    <p class="text-muted mb-0">{{ $reuniao->descricao }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Participantes -->
        <div class="metric-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="metric-label mb-0">Participantes ({{ $reuniao->participantes->count() }})</h3>
                <div class="d-flex gap-2">
                    @if($reuniao->participantes->count() > 0 && in_array($reuniao->status, ['agendada', 'confirmada']))
                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#enviarConvitesModal">
                            <i class="bi bi-envelope me-1"></i>Enviar Convites
                        </button>
                    @endif
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#adicionarParticipanteModal">
                        <i class="bi bi-plus-lg me-1"></i>Adicionar
                    </button>
                </div>
            </div>

            @forelse($reuniao->participantes as $participante)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <strong>{{ $participante->user?->name ?? 'Usuário removido' }}</strong>
                        <small class="text-muted d-block">{{ $participante->user?->email ?? 'N/A' }}</small>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        @if($participante->confirmado)
                            <span class="badge bg-success">Confirmado</span>
                        @else
                            <span class="badge bg-secondary">Pendente</span>
                        @endif
                        @if($participante->presente)
                            <span class="badge bg-primary">Presente</span>
                        @endif
                        @if($participante->user)
                        <button type="button" class="btn btn-sm btn-outline-danger btnRemoverParticipante" 
                                data-user-id="{{ $participante->user->id }}" 
                                data-user-name="{{ $participante->user->name }}">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="removeParticipanteForm{{ $participante->user->id }}" 
                              method="POST" 
                              action="{{ route('reunioes.participantes.remove', [$reuniao, $participante->user]) }}" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        @else
                            <span class="text-muted small">Usuário removido</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted">Nenhum participante adicionado</p>
            @endforelse
        </div>

        <!-- Atas -->
        <div class="metric-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="metric-label mb-0">Atas ({{ $reuniao->atas->count() }})</h3>
                <a href="{{ route('atas.create', $reuniao) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Criar Ata
                </a>
            </div>
            @if($reuniao->atas->count() > 0)
                @foreach($reuniao->atas as $ata)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <strong>Ata #{{ $ata->numero ?? $ata->id }}</strong>
                            <small class="text-muted d-block">
                                {{ $ata->aprovada ? 'Aprovada' : 'Pendente de aprovação' }}
                                - {{ $ata->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                        <a href="{{ route('atas.show', $ata) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Visualizar
                        </a>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Nenhuma ata criada ainda.</p>
            @endif
        </div>

        <!-- Lembretes Configurados -->
        <div class="metric-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="metric-label mb-0">Lembretes Configurados ({{ $reuniao->lembretes->count() }})</h3>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#definirLembretesModal">
                    <i class="bi bi-bell me-1"></i>{{ $reuniao->lembretes->count() > 0 ? 'Editar' : 'Definir' }} Lembretes
                </button>
            </div>
            @if($reuniao->lembretes->count() > 0)
                @foreach($reuniao->lembretes as $lembrete)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <strong>{{ $lembrete->tipo_label }}</strong>
                            <small class="text-muted d-block">
                                @if($lembrete->enviado)
                                    Enviado em {{ $lembrete->data_envio->format('d/m/Y H:i') }}
                                @else
                                    Pendente de envio
                                @endif
                            </small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger btnRemoverLembrete" 
                                data-lembrete-id="{{ $lembrete->id }}" 
                                data-lembrete-tipo="{{ $lembrete->tipo_label }}">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="removeLembreteForm{{ $lembrete->id }}" 
                              method="POST" 
                              action="{{ route('reunioes.lembretes.remove', [$reuniao, $lembrete]) }}" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                @endforeach
            @else
                <p class="text-muted mb-0">Nenhum lembrete configurado. Clique em "Definir Lembretes" para configurar.</p>
            @endif
        </div>

        <!-- Decisões -->
        <div class="metric-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="metric-label mb-0">Decisões Tomadas ({{ $reuniao->decisoes->count() }})</h3>
                <a href="{{ route('decisoes.create', $reuniao) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nova Decisão
                </a>
            </div>
            @if($reuniao->decisoes->count() > 0)
                @foreach($reuniao->decisoes as $decisao)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div class="flex-grow-1">
                            <strong>
                                <a href="{{ route('decisoes.show', $decisao) }}" class="text-decoration-none">
                                    {{ $decisao->titulo }}
                                </a>
                            </strong>
                            <small class="text-muted d-block">
                                Status: <span class="badge bg-{{ $decisao->status == 'concluida' ? 'success' : ($decisao->status == 'em_andamento' ? 'primary' : 'secondary') }}">{{ ucfirst($decisao->status) }}</span>
                                @if($decisao->prazo)
                                    - Prazo: {{ $decisao->prazo->format('d/m/Y') }}
                                @endif
                                @if($decisao->responsavel)
                                    - Responsável: {{ $decisao->responsavel->name }}
                                @endif
                            </small>
                            @if($decisao->descricao)
                                <small class="text-muted d-block mt-1">{{ Str::limit($decisao->descricao, 100) }}</small>
                            @endif
                        </div>
                        <div class="d-flex gap-2 ms-3">
                            <a href="{{ route('decisoes.edit', $decisao) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted mb-0">Nenhuma decisão registrada ainda.</p>
            @endif
        </div>
    </div>
</div>

<!-- Modal Adicionar Participante -->
<div class="modal fade" id="adicionarParticipanteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('reunioes.participantes.add', $reuniao) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Participante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Usuário</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Selecione...</option>
                            @foreach($usuarios as $usuario)
                                @if(!$reuniao->participantes->contains('user_id', $usuario->id))
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Enviar Convites -->
<div class="modal fade" id="enviarConvitesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar Convites</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Enviar convites por e-mail para os participantes da reunião:</p>
                <div class="mb-3">
                    <strong>{{ $reuniao->titulo }}</strong>
                    <p class="text-muted small mb-0">
                        {{ $reuniao->data_hora->format('d/m/Y H:i') }} - {{ $reuniao->local ?? 'Local não definido' }}
                    </p>
                </div>
                <div class="list-group mb-3">
                    @foreach($reuniao->participantes as $participante)
                        <div class="list-group-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="participante_{{ $participante->id }}" checked>
                                <label class="form-check-label" for="participante_{{ $participante->id }}">
                                    {{ $participante->user?->name ?? 'Usuário removido' }} <small class="text-muted">({{ $participante->user?->email ?? 'N/A' }})</small>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>Funcionalidade de envio de e-mail será implementada em breve. Por enquanto, os convites podem ser enviados manualmente.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="alert('Funcionalidade de envio de e-mail será implementada em breve.');">
                    <i class="bi bi-envelope me-1"></i>Enviar Convites
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Definir Lembretes -->
<div class="modal fade" id="definirLembretesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('reunioes.lembretes.save', $reuniao) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Definir Lembretes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Configurar lembretes automáticos para a reunião:</p>
                    <div class="mb-3">
                        <strong>{{ $reuniao->titulo }}</strong>
                        <p class="text-muted small mb-0">
                            {{ $reuniao->data_hora->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lembretes</label>
                        @php
                            $lembretesConfigurados = $reuniao->lembretes->pluck('tipo')->toArray();
                        @endphp
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lembretes[]" value="1_dia" id="lembrete_1dia" {{ in_array('1_dia', $lembretesConfigurados) ? 'checked' : '' }}>
                            <label class="form-check-label" for="lembrete_1dia">
                                1 dia antes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lembretes[]" value="3_dias" id="lembrete_3dias" {{ in_array('3_dias', $lembretesConfigurados) ? 'checked' : '' }}>
                            <label class="form-check-label" for="lembrete_3dias">
                                3 dias antes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lembretes[]" value="1_semana" id="lembrete_1semana" {{ in_array('1_semana', $lembretesConfigurados) ? 'checked' : '' }}>
                            <label class="form-check-label" for="lembrete_1semana">
                                1 semana antes
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Os lembretes serão exibidos nas notificações do sistema.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-bell me-1"></i>Salvar Lembretes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remover Participante
    const botoesRemoverParticipante = document.querySelectorAll('.btnRemoverParticipante');
    botoesRemoverParticipante.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const form = document.getElementById('removeParticipanteForm' + userId);
            
            if (form) {
                Swal.fire({
                    title: 'Remover participante?',
                    html: `Deseja realmente remover <strong>"${userName}"</strong> desta reunião?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sim, remover!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });

    // Remover Lembrete
    const botoesRemoverLembrete = document.querySelectorAll('.btnRemoverLembrete');
    botoesRemoverLembrete.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const lembreteId = this.getAttribute('data-lembrete-id');
            const lembreteTipo = this.getAttribute('data-lembrete-tipo');
            const form = document.getElementById('removeLembreteForm' + lembreteId);
            
            if (form) {
                Swal.fire({
                    title: 'Remover lembrete?',
                    html: `Deseja realmente remover o lembrete <strong>"${lembreteTipo}"</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sim, remover!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection

