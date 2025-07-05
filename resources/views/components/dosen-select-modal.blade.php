{{--
    Modal for selecting a lecturer.
    USAGE:
    @include('components.dosen-select-modal', [
        'dosens' => $dosens,
        'modalId' => 'dosenPembimbingModal',
        'inputName' => 'dosen_pembimbing_id',
        'displayName' => 'dosen_pembimbing_display'
    ])
--}}
<div class="modal-backdrop" id="{{ $modalId }}-backdrop" style="display: none;"></div>
<div class="modal" id="{{ $modalId }}" style="display: none;">
    <div class="modal-header">
        <h3 class="modal-title">Pilih Dosen</h3>
        <button type="button" class="modal-close" onclick="closeDosenModal('{{ $modalId }}')">&times;</button>
    </div>
    <div class="modal-body">
        <input type="text" class="form-control mb-3" id="{{ $modalId }}-search" onkeyup="filterDosenList('{{ $modalId }}')" placeholder="Cari nama dosen...">
        <ul class="dosen-list">
            @foreach ($dosens as $dosen)
                <li data-id="{{ $dosen->id }}" data-name="{{ $dosen->nama }}" onclick="selectDosen('{{ $modalId }}', '{{ $inputName }}', '{{ $displayName }}', this)">
                    {{ $dosen->nama }}
                    <span class="text-muted d-block small">{{ $dosen->nidn }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
