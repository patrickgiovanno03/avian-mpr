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
                            <label class="form-label fw-semibold d-block mb-3">Pilih Style</label>
                            <div>
                                @foreach ($styles as $style)
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input styleCheckbox" value="{{ $style['value'] }}" data-label="{{ $style['label'] }}" id="checkbox-{{ $style['value'] }}">
                                        <label class="form-check-label" for="checkbox-{{ $style['value'] }}">{{ $style['label'] }}</label>
                                    </div>
                                @endforeach
                            </div>
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
        padding: 4px 16px !important; atas-bawah 12px, kiri-kanan 16px
        vertical-align: middle;
    }

    input[type="checkbox"]:not(#show_all) {
        width: 20px;
        height: 20px;
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

    function updateTextareas() {
        textareasContainer.innerHTML = '';
        const checkedStyles = Array.from(styleCheckboxes).filter(cb => cb.checked);

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

    // Form submit handler untuk validasi
    namaForm.addEventListener('submit', function(e) {
        const checkedStyles = Array.from(styleCheckboxes).filter(cb => cb.checked);
        if (checkedStyles.length === 0) {
            e.preventDefault();
            alert('Pilih minimal 1 style untuk melanjutkan');
        }
    });
</script>
@endsection
