@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('content')

    <!-- PAGE BODY -->
    <main class="page-body">
        <!-- FORM CONTAINER -->
        <div class="row justify-content-center">
            <div class="col-12 col-xl-9">

                <div class="form-card fade-up">
                    @csrf
                    <div class="form-card-header">
                        <div class="form-header-icon"><i class="bi bi-pencil-square"></i></div>
                        <div>
                            <h3>Edit Laporan</h3>
                            <p>Semua kolom bertanda <span style="color:var(--red-vivid);">*</span> wajib diisi</p>
                        </div>
                    </div>

                    <form action="{{ route('siswa.update', $report->id) }}" method="POST" enctype="multipart/form-data"
                        id="formLaporan">
                        @csrf
                        @method('PUT')
                        <div class="form-card-body">

                            {{-- Tampilkan error validasi Laravel --}}
                            @if ($errors->any())
                                <div class="alert-laporan error mb-4">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <div>
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- SECTION: INFO LAPORAN -->
                            <div class="form-section-label"><i class="bi bi-info-circle-fill"></i> Informasi Laporan
                            </div>

                            <!-- Nama Laporan -->
                            <div class="mb-4">
                                <label class="form-label-custom">
                                    Judul Laporan <span class="required">*</span>
                                </label>
                                <input type="text" name="title" id="inputJudul" class="input-custom"
                                    placeholder="Apa masalah yang anda temui?" maxlength="100"
                                    oninput="updateChar(this,'charJudul')" required
                                    value="{{ old('title', $report->title) }}" />
                                <div class="char-count"><span id="charJudul">0</span> / 100</div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-4">
                                <label class="form-label-custom">
                                    Deskripsi Laporan <span class="required">*</span>
                                </label>
                                <textarea id="inputDesc" name="description" class="input-custom" rows="5"
                                    placeholder="Deskripsikan apa yang anda temukan" maxlength="1000" oninput="updateChar(this,'charDesc')" required
                                    value="{{ old('description', $report->description) }}">{{ old('description', $report->description) }}</textarea>
                                <div class="char-count"><span id="charDesc">0</span> / 1000</div>
                            </div>

                            <!-- SECTION: LOKASI -->
                            <div class="form-section-label"><i class="bi bi-geo-alt-fill"></i> Lokasi </div>

                            <div class="mb-4">
                                <label class="form-label-custom">
                                    Lokasi <span class="required">*</span>
                                </label>
                                <div class="location-input-wrap">
                                    <input type="text" name="location" id="inputLokasi" class="input-custom"
                                        placeholder="Dimana kamu menemukan masalah ini?"
                                        value="{{ old('location', $report->location) }}" />
                                </div>
                                <div id="locateMsg"
                                    style="font-size:.74rem;color:var(--gray-400);margin-top:5px;display:none;"></div>
                            </div>

                            <!-- SECTION: FOTO -->
                            <div class="form-section-label"><i class="bi bi-camera-fill"></i> Foto Bukti</div>

                            <div class="info-pill">
                                <i class="bi bi-info-circle-fill"></i>
                                Anda dapat menambahkan hingga 5 foto sebagai bukti laporan
                            </div>

                            <!-- Photo count indicator -->
                            <div class="photo-count-bar">
                                <span class="photo-count-label">Foto terpilih: <b id="photoCountNum">{{ count($report->images) }}</b>/5</span>
                                <div class="photo-dots" id="photoDots">
                                    <div class="photo-dot" id="dot0"></div>
                                    <div class="photo-dot" id="dot1"></div>
                                    <div class="photo-dot" id="dot2"></div>
                                    <div class="photo-dot" id="dot3"></div>
                                    <div class="photo-dot" id="dot4"></div>
                                </div>
                            </div>

                            <!-- Source buttons -->
                            <div class="photo-source-btns">
                                <button type="button" class="btn-source"
                                    onclick="document.getElementById('fileInput').click()">
                                    <i class="bi bi-folder2-open"></i> Pilih dari File
                                </button>
                                <button type="button" class="btn-source" onclick="openCamera()">
                                    <i class="bi bi-camera-fill"></i> Foto Langsung
                                </button>
                            </div>
                            <input type="file" id="fileInput" name="images[]" accept="image/*" multiple
                                style="display:none" onchange="handleFileSelect(event)" />
                            <div id="deletedImagesContainer"></div>


                            <!-- Photo grid -->

                            <div class="photo-grid" id="photoGrid">
                                <!-- Filled by JS for edit mode -->
                            </div>

                            <div style="margin-top:10px;">
                                <span style="font-size:.73rem;color:var(--gray-400);">Format: JPG, PNG, WEBP · Maks.
                                    10MB
                                    per foto</span>
                            </div>



                        </div><!-- /.form-card-body -->
                        <div class="form-card-footer">
                            <a href="{{ route('siswa.dashboard') }}" class="btn-cancel"><i
                                    class="bi bi-arrow-left me-1"></i> Batal</a>
                            <button type="submit" class="btn-submit" onclick="submitForm()">
                                <i class="bi bi-send-fill"></i> Update Laporan
                            </button>
                        </div>
                    </form>

                    <!-- FOOTER ACTIONS -->

                </div><!-- /.form-card -->
            </div>
        </div>

    </main>

    <!-- MODAL KAMERA -->
    <div class="modal fade" id="cameraModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Camera viewport -->
                <div class="camera-wrapper" id="cameraWrapper">
                    <!-- Loading state -->
                    <div class="cam-loading" id="camLoading">
                        <div class="spinner"></div>
                        <span>Mengaktifkan kamera...</span>
                    </div>
                    <!-- Video stream -->
                    <video id="cameraStream" autoplay playsinline style="display:none;"></video>
                    <!-- Viewfinder overlay -->
                    <div class="viewfinder-overlay" id="viewfinderOverlay" style="display:none;">
                        <div class="vf-corner tl"></div>
                        <div class="vf-corner tr"></div>
                        <div class="vf-corner bl"></div>
                        <div class="vf-corner br"></div>
                        <div class="vf-crosshair"></div>
                    </div>
                    <!-- Top bar -->
                    <div class="camera-top-bar" id="camTopBar" style="display:none;">
                        <span class="cam-counter text-white" id="camCounter">0 / 5</span>
                    </div>
                    <!-- Flash -->
                    <div class="camera-flash" id="cameraFlash"></div>
                    <!-- Error state -->
                    <div class="cam-error" id="camError" style="display:none;">
                        <i class="bi bi-camera-video-off-fill"></i>
                        <div>Kamera tidak dapat diakses.<br>Periksa izin kamera di browser Anda.</div>
                        <button class="btn-cam-done" onclick="closeCameraModal()" style="margin-top:6px;">Tutup</button>
                    </div>
                </div>

                <!-- Controls -->
                <div class="camera-controls" id="camControls" style="display:none;">
                    <!-- Thumbnails strip -->
                    <div class="cam-thumb-strip" id="camThumbStrip">
                        <!-- filled by JS -->
                    </div>
                    <!-- Shutter -->
                    <button class="btn-shutter" id="btnShutter" onclick="capturePhoto()">
                        <div class="btn-shutter-inner"></div>
                    </button>
                    <!-- Flip + Done -->
                    <div class="d-flex flex-column align-items-center gap-2">
                        <button class="btn-cam-flip" onclick="flipCamera()" title="Balik kamera">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                        <button class="btn-cam-done" onclick="closeCameraModal()">Selesai</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <canvas id="captureCanvas" style="display:none;"></canvas>


@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('templates/js/create.js') }}"></script>
@endpush
