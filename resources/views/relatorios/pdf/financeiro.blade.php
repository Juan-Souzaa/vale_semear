<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #16a34a;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .resumo {
            background-color: #f0fdf4;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #16a34a;
        }
        .resumo h2 {
            margin-top: 0;
            color: #16a34a;
            font-size: 16px;
            border-bottom: 1px solid #bbf7d0;
            padding-bottom: 5px;
        }
        .resumo-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .resumo-item:last-child {
            border-bottom: none;
        }
        .resumo-label {
            font-weight: bold;
            color: #15803d;
        }
        .resumo-value {
            color: #1f2937;
            font-weight: bold;
        }
        .total {
            background-color: #dcfce7;
            font-weight: bold;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #16a34a;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SEMEAR</h1>
        <p>Sistema de Gestão Operacional</p>
        <p>Relatório Financeiro</p>
        <p>Período: {{ \Carbon\Carbon::parse($periodoInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($periodoFim)->format('d/m/Y') }}</p>
    </div>

    @if(!empty($dados['resumo']))
    <div class="resumo">
        <h2>Resumo Financeiro</h2>
        @foreach($dados['resumo'] as $chave => $valor)
        <div class="resumo-item {{ str_contains($chave, 'Total') ? 'total' : '' }}">
            <span class="resumo-label">{{ $chave }}:</span>
            <span class="resumo-value">{{ $valor }}</span>
        </div>
        @endforeach
    </div>
    @endif

    @if(!empty($dados['detalhes']))
    <h2 style="color: #1f2937; font-size: 16px; margin-top: 20px;">Detalhamento por Atividade</h2>
    <table>
        <thead>
            <tr>
                @foreach(array_keys($dados['detalhes'][0]) as $coluna)
                <th>{{ $coluna }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($dados['detalhes'] as $linha)
            <tr>
                @foreach($linha as $valor)
                <td>{{ $valor }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #6b7280; margin-top: 30px;">Nenhuma atividade com orçamento encontrada no período selecionado.</p>
    @endif

    <div class="footer">
        <p>Relatório gerado em {{ now()->format('d/m/Y H:i:s') }} por {{ $usuario->name ?? 'Sistema' }}</p>
    </div>
</body>
</html>

