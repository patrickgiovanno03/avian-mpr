@extends('layouts.app')

@section('title', 'MPR | Generate Nama')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">

                    <h4 class="mb-3 fw-bold">Generate Nama</h4>
                    <p class="text-muted small mb-4">
                        Masukkan beberapa nama (pisahkan dengan Enter), lalu pilih tipe style.
                    </p>

                    <form method="POST" action="{{ route('nama.generate') }}" id="namaForm">
                        @csrf

                        <?php
                        $styles = [
                            ['value' => 'normal', 'label' => 'Normal'],
                            ['value' => 'outline', 'label' => 'Outline'],
                            ['value' => 'mc', 'label' => 'MC'],
                            ['value' => 'mini', 'label' => 'Mini'],
                            ['value' => 'flex', 'label' => 'Flexible'],
                            ['value' => '6', 'label' => 'Barbie'],
                            ['value' => '6mini', 'label' => 'Barbie Mini'],
                            ['value' => 'bob', 'label' => 'Spongebob'],
                            ['value' => 'stand', 'label' => 'Standing'],
                            ['value' => 'bunga', 'label' => 'Flower'],
                            ['value' => 'mario', 'label' => 'Mario'],
                            ['value' => 'pokemon', 'label' => 'Pokemon'],
                        ];
                        ?>
                        
                        <!-- Pilih Style (Checkbox) -->
                        <div class="mb-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <label class="form-label fw-semibold mb-0">Pilih Style</label>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi cepat style">
                                    <button type="button" class="btn btn-outline-secondary" id="selectAllStyles">Pilih Semua</button>
                                    <button type="button" class="btn btn-outline-secondary" id="invertStyles">Balik Pilihan</button>
                                    <button type="button" class="btn btn-outline-secondary" id="clearStyles">Kosongkan</button>
                                </div>
                            </div>

                            <div class="style-grid" id="styleGrid">
                                @foreach ($styles as $style)
                                    <div class="style-item">
                                        <input
                                            type="checkbox"
                                            class="styleCheckbox style-input"
                                            value="{{ $style['value'] }}"
                                            data-label="{{ $style['label'] }}"
                                            id="checkbox-{{ $style['value'] }}"
                                        >
                                        <label class="style-chip" for="checkbox-{{ $style['value'] }}">{{ $style['label'] }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <p class="text-muted small mt-2 mb-0" id="selectedStylesInfo">0 style dipilih</p>
                        </div>

                        <!-- Dynamic Textareas Container -->
                        <div id="textareasContainer" class="mb-3">
                            <!-- Textareas akan di-generate oleh JavaScript -->
                        </div>

                        <!-- Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-avian-primary btn-lg">
                                Generate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@section('css')
<style>
    #printModal .modal-xl {
        max-width: 95%;
    }
    div.dataTables_filter, div.dataTables_length {
        padding: 2% 2% 0% 2%;
    }
    div.dataTables_info, div.dataTables_paginate {
        padding: 0% 2% 1% 2%;
    }
    table.dataTable tbody td {
        padding: 4px 16px !important; /* atas-bawah 12px, kiri-kanan 16px */
        vertical-align: middle;
    }

    .style-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
    }

    .style-item {
        position: relative;
    }

    .style-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .style-chip {
        display: block;
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 999px;
        text-align: center;
        font-size: 0.9rem;
        cursor: pointer;
        user-select: none;
        background: #fff;
        transition: all 0.18s ease;
    }

    .style-chip:hover {
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .style-input:checked + .style-chip {
        background: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.22);
    }

    .style-input:focus + .style-chip,
    .style-input:focus-visible + .style-chip {
        outline: 2px solid rgba(13, 110, 253, 0.45);
        outline-offset: 2px;
    }
    
    @media (max-width: 768px) {
        table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child {
            padding-left: 30px !important; /* geser teks biar gak ketimpa tombol + */
        }
    }
</style>
@endsection

@section('js')
<script>
    const styleCheckboxes = document.querySelectorAll('.styleCheckbox');
    const textareasContainer = document.getElementById('textareasContainer');
    const namaForm = document.getElementById('namaForm');
    const selectedStylesInfo = document.getElementById('selectedStylesInfo');

    const selectAllStylesBtn = document.getElementById('selectAllStyles');
    const clearStylesBtn = document.getElementById('clearStyles');
    const invertStylesBtn = document.getElementById('invertStyles');

    function getCheckedStyles() {
        return Array.from(styleCheckboxes).filter(cb => cb.checked);
    }

    function updateSelectedInfo() {
        const selectedCount = getCheckedStyles().length;
        selectedStylesInfo.textContent = `${selectedCount} style dipilih`;
    }

    function applyStylesFromUrl() {
        const params = new URLSearchParams(window.location.search);
        const selectedFromUrl = new Set();

        function collectValues(paramName) {
            params.getAll(paramName).forEach(value => {
                value
                    .split(',')
                    .map(item => item.trim())
                    .filter(Boolean)
                    .forEach(item => selectedFromUrl.add(item));
            });
        }

        collectValues('style');
        collectValues('styles');
        collectValues('style[]');
        collectValues('styles[]');

        if (selectedFromUrl.size === 0) {
            return;
        }

        styleCheckboxes.forEach(checkbox => {
            checkbox.checked = selectedFromUrl.has(checkbox.value);
        });
    }

    function syncStylesToUrl() {
        const url = new URL(window.location.href);

        url.searchParams.delete('style');
        url.searchParams.delete('styles');
        url.searchParams.delete('style[]');
        url.searchParams.delete('styles[]');

        getCheckedStyles().forEach(checkbox => {
            url.searchParams.append('style[]', checkbox.value);
        });

        window.history.replaceState({}, '', url.toString());
    }

    function updateTextareas() {
        textareasContainer.innerHTML = '';
        const checkedStyles = getCheckedStyles();

        updateSelectedInfo();
        syncStylesToUrl();

        if (checkedStyles.length === 0) {
            textareasContainer.innerHTML = '<p class="text-muted small">Pilih minimal 1 style untuk memulai</p>';
            return;
        }

        checkedStyles.forEach(checkbox => {
            const styleValue = checkbox.value;
            const styleLabel = checkbox.dataset.label;

            const textareaDiv = document.createElement('div');
            textareaDiv.className = 'mb-3';
            textareaDiv.innerHTML = `
                <label for="text-${styleValue}" class="form-label fw-semibold">
                    Daftar Nama - ${styleLabel}
                </label>
                <textarea 
                    id="text-${styleValue}"
                    name="text[${styleValue}]" 
                    class="form-control" 
                    rows="5" 
                    placeholder="Nama 1&#10;Nama 2&#10;Nama 3"
                    required></textarea>
            `;
            textareasContainer.appendChild(textareaDiv);
        });
    }

    // Event listener untuk semua checkbox
    styleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTextareas);
    });

    selectAllStylesBtn.addEventListener('click', function() {
        styleCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateTextareas();
    });

    clearStylesBtn.addEventListener('click', function() {
        styleCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateTextareas();
    });

    invertStylesBtn.addEventListener('click', function() {
        styleCheckboxes.forEach(checkbox => {
            checkbox.checked = !checkbox.checked;
        });
        updateTextareas();
    });

    applyStylesFromUrl();
    updateTextareas();

    // Form submit handler untuk validasi
    namaForm.addEventListener('submit', function(e) {
        const checkedStyles = getCheckedStyles();
        if (checkedStyles.length === 0) {
            e.preventDefault();
            alert('Pilih minimal 1 style untuk melanjutkan');
        }
    });
</script>
@endsection
