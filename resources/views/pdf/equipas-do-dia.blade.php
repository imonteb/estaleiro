<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        .equipa { margin-bottom: 15px; border-bottom: 1px dashed #ccc; padding-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Equipas Criadas para {{ $data }}</h1>

    @forelse ($equipas as $team)
        <div class="equipa">
            <strong>{{ $team->name }}</strong><br>
            PEP: {{ $team->pep->code }}<br>
            Local: {{ $team->address_of_work ?? '—' }}<br>
            Líder: {{ optional($team->teamleader)->full_name ?? '—' }}<br>
            Status: {{ $team->is_published ? 'Publicado' : 'Em preparação' }}
        </div>
    @empty
        <p>Nenhuma equipa encontrada.</p>
    @endforelse
</body>
</html>
