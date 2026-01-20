@php
    $statusConfig = [
        'Diajukan' => ['bg' => 'bg-warning', 'text' => 'text-dark', 'icon' => '🕒'],
        'Disetujui' => ['bg' => 'bg-success', 'text' => 'text-white', 'icon' => '✅'],
        'Ditolak' => ['bg' => 'bg-danger', 'text' => 'text-white', 'icon' => '❌'],
    ];
    $config = $statusConfig[$selected] ?? $statusConfig['Diajukan'];
@endphp
<div class="dropdown">
    <button type="button" 
        class="btn btn-sm {{ $config['bg'] }} {{ $config['text'] }} dropdown-toggle px-2 py-1" 
        data-bs-toggle="dropdown" 
        aria-expanded="false"
        style="font-size: 0.75rem; min-width: 90px;">
        {{ $config['icon'] }} {{ $selected }}
    </button>
    <ul class="dropdown-menu dropdown-menu-sm">
        @foreach($options as $key => $label)
            <li>
                <a class="dropdown-item {{ $selected == $key ? 'active' : '' }}" 
                   href="#" 
                   wire:click.prevent="$dispatch('statusChanged', { status: '{{ $key }}', id: '{{ $rowId }}' })">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
