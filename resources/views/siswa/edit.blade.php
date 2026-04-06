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
    <script>
        const MAX_PHOTOS = 5;
        let photos = []; // [{ dataUrl: string, file?: File }]
        let stream = null;
        let facingMode = 'environment';
        let cameraReady = false; // flag: metadata video sudah dimuat
        
        let deletedImages = [];
        const initialExistingCount = {{ count($report->images) }};
        const existingImageData = [
            @foreach($report->images as $image)
            {id: {{ $image->id }}, dataUrl: "{{ url('storage/' . $image->path) }}"},
            @endforeach
        ];
        const getTotalPhotos = () => initialExistingCount - deletedImages.length + photos.length;


        /* ══════════════════════════════════════════════════════════
           PHOTO MANAGEMENT
        ══════════════════════════════════════════════════════════ */

        /**
         * Render ulang seluruh photo grid berdasarkan array `photos`.
         */
        function renderPhotoGrid() {
            const grid = document.getElementById('photoGrid');
            if (!grid) return;
            grid.innerHTML = '';

            let currentNum = 1;

            // Render existing images that are not deleted
            existingImageData.forEach(imgData => {
                if (!deletedImages.includes(imgData.id)) {
                    const slot = document.createElement('div');
                    slot.className = 'photo-slot has-image';
                    slot.dataset.imageId = imgData.id;
                    slot.innerHTML = `
                        <img src="${imgData.dataUrl}" alt="Foto ${currentNum}" loading="lazy">
                        <button type="button" class="remove-photo" title="Hapus foto" onclick="removeExisting(${imgData.id})">
                            <i class="bi bi-x"></i>
                        </button>
                        <span class="photo-num">${currentNum}</span>
                    `;
                    grid.appendChild(slot);
                    currentNum++;
                }
            });

            // Render new photos
            photos.forEach((p, i) => {
                const slot = document.createElement('div');
                slot.className = 'photo-slot has-image';
                slot.innerHTML = `
                    <img src="${p.dataUrl}" alt="Foto ${currentNum}" >
                    <button type="button" class="remove-photo" title="Hapus foto" onclick="removeNew(${photos.indexOf(p)})">
                        <i class="bi bi-x"></i>
                    </button>
                    <span class="photo-num">${currentNum}</span>
                `;
                grid.appendChild(slot);
                currentNum++;
            });

            // Add slot if can add more
            if (getTotalPhotos() < MAX_PHOTOS) {
                const addSlot = document.createElement('div');
                addSlot.className = 'photo-add-slot';
                addSlot.setAttribute('role', 'button');
                addSlot.setAttribute('tabindex', '0');
                addSlot.innerHTML = `
                    <div class="slot-icon"><i class="bi bi-plus-lg"></i></div>
                    <div class="slot-label">Tambah Foto</div>
                `;
                addSlot.onclick = () => document.getElementById('fileInput').click();
                addSlot.onkeydown = (e) => {
                    if (e.key === 'Enter') addSlot.click();
                };
                grid.appendChild(addSlot);

                // Empty slots
                const emptyCount = MAX_PHOTOS - getTotalPhotos() - 1;
                for (let i = 0; i < emptyCount; i++) {
                    const empty = document.createElement('div');
                    empty.className = 'photo-slot';
                    empty.style.cursor = 'default';
                    empty.innerHTML = `<div class="slot-icon"><i class="bi bi-image" style="font-size:.9rem;"></i></div>`;
                    grid.appendChild(empty);
                }
            }

            updateTotalCount();
        }

        function removeExisting(imageId) {
            if (deletedImages.includes(imageId)) return;
            deletedImages.push(imageId);
            renderPhotoGrid();
            updateTotalDots();
        }

        function removeNew(index) {
            photos.splice(index, 1);
            renderPhotoGrid();
            updateCamCounter();
            updateTotalDots();
        }

        /**
         * Update indikator jumlah foto (teks + titik-titik).
         */
        function updateTotalCount() {
            const numEl = document.getElementById('photoCountNum');
            if (numEl) numEl.textContent = getTotalPhotos();

            updateTotalDots();
        }

        function updateTotalDots() {
            for (let i = 0; i < MAX_PHOTOS; i++) {
                const dot = document.getElementById('dot' + i);
                if (dot) dot.classList.toggle('filled', i < getTotalPhotos());
            }
        }

        /**
         * Hapus foto pada index tertentu.
         */
        function removePhoto(index) {
            photos.splice(index, 1);
            renderPhotoGrid();
            updateCamCounter();
        }

        /**
         * Handler untuk input file (dari galeri / file picker).
         */
        function handleFileSelect(e) {
            if (getTotalPhotos() >= MAX_PHOTOS) {
                showToast('Batas maksimal 5 foto sudah tercapai.', 'warning');
                return;
            }

            const files = Array.from(e.target.files);
            if (!files.length) return;

            const allowed = MAX_PHOTOS - getTotalPhotos();
            const toAdd = files.slice(0, allowed);
            let loaded = 0;

            toAdd.forEach(file => {
                // Validasi tipe & ukuran
                if (!file.type.startsWith('image/')) {
                    showToast(`File "${file.name}" bukan gambar.`, 'error');
                    if (++loaded === toAdd.length) renderPhotoGrid();
                    return;
                }
                if (file.size > 10 * 1024 * 1024) {
                    showToast(`File "${file.name}" melebihi batas 10MB.`, 'error');
                    if (++loaded === toAdd.length) renderPhotoGrid();
                    return;
                }

                const reader = new FileReader();
                reader.onload = ev => {
                    photos.push({
                        dataUrl: ev.target.result,
                        file
                    });
                    if (++loaded === toAdd.length) renderPhotoGrid();
                };
                reader.readAsDataURL(file);
            });

            // Reset input agar file yang sama bisa dipilih ulang
            e.target.value = '';
        }


        /* ══════════════════════════════════════════════════════════
           KAMERA
        ══════════════════════════════════════════════════════════ */

        /**
         * Buka modal kamera.
         */
        function openCamera() {
            if (getTotalPhotos() >= MAX_PHOTOS) {
                showToast('Batas maksimal 5 foto sudah tercapai.', 'warning');
                return;
            }

            const modalEl = document.getElementById('cameraModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
            startStream();
        }

        /**
         * Mulai stream kamera.
         * FIX: Set `cameraReady = false` dan tunggu event `loadedmetadata`
         *      sebelum mengizinkan capture, sehingga videoWidth/Height tidak 0.
         */
        async function startStream() {
            cameraReady = false;

            // Reset UI ke state loading
            setEl('camLoading', {
                display: 'flex'
            });
            setEl('cameraStream', {
                display: 'none'
            });
            setEl('viewfinderOverlay', {
                display: 'none'
            });
            setEl('camTopBar', {
                display: 'none'
            });
            setEl('camControls', {
                display: 'none'
            });
            setEl('camError', {
                display: 'none'
            });

            stopStream();

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode,
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 960
                        },
                    },
                    audio: false,
                });

                const video = document.getElementById('cameraStream');
                video.srcObject = stream;

                // ─── FIX UTAMA ───────────────────────────────────────
                // Tunggu loadedmetadata agar videoWidth & videoHeight
                // sudah terisi sebelum capture diizinkan.
                video.onloadedmetadata = () => {
                    cameraReady = true;
                };
                // ─────────────────────────────────────────────────────

                video.style.display = 'block';
                setEl('camLoading', {
                    display: 'none'
                });
                setEl('viewfinderOverlay', {
                    display: 'block'
                });
                setEl('camTopBar', {
                    display: 'flex'
                });
                setEl('camControls', {
                    display: 'flex'
                });

                updateCamCounter();
                renderCamThumbs();

            } catch (err) {
                console.error('Camera error:', err);
                setEl('camLoading', {
                    display: 'none'
                });
                setEl('camError', {
                    display: 'flex'
                });
            }
        }

        /**
         * Hentikan semua track stream aktif.
         */
        function stopStream() {
            if (stream) {
                stream.getTracks().forEach(t => t.stop());
                stream = null;
            }
            cameraReady = false;
        }

        /**
         * Balik kamera (depan ↔ belakang).
         */
        function flipCamera() {
            facingMode = facingMode === 'environment' ? 'user' : 'environment';
            startStream();
        }

        /**
         * Ambil foto dari stream video.
         * FIX: Cek `cameraReady` dan gunakan fallback dimensi jika masih 0.
         */
        function capturePhoto() {
            if (getTotalPhotos() >= MAX_PHOTOS) {
                showToast('Batas maksimal 5 foto sudah tercapai.', 'warning');
                closeCameraModal();
                return;
            }

            const video = document.getElementById('cameraStream');
            const canvas = document.getElementById('captureCanvas');

            // ─── FIX UTAMA ─────────────────────────────────────────
            // Jika metadata belum siap, videoWidth/Height bisa = 0.
            // Gunakan fallback 1280x960 agar capture tidak gagal diam-diam.
            const w = video.videoWidth || 1280;
            const h = video.videoHeight || 960;

            if (!cameraReady || w === 0 || h === 0) {
                showToast('Kamera belum siap, coba lagi sebentar.', 'warning');
                return;
            }
            // ───────────────────────────────────────────────────────

            canvas.width = w;
            canvas.height = h;
            canvas.getContext('2d').drawImage(video, 0, 0, w, h);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);

            // Validasi hasil capture tidak kosong
            if (!dataUrl || dataUrl === 'data:,') {
                showToast('Gagal mengambil foto, coba lagi.', 'error');
                return;
            }

            // Efek flash
            triggerFlash();

            photos.push({
                dataUrl
            });
            renderCamThumbs();
            updateCamCounter();

            // Auto-tutup modal saat foto sudah mencapai maksimal
            if (photos.length >= MAX_PHOTOS) {
                setTimeout(() => closeCameraModal(), 500);
            }
        }

        /**
         * Tutup modal kamera & render ulang grid.
         */
        function closeCameraModal() {
            stopStream();
            const modalEl = document.getElementById('cameraModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            renderPhotoGrid();
        }

        /**
         * Update counter & disable shutter jika sudah penuh.
         */
        function updateCamCounter() {
            const el = document.getElementById('camCounter');
            if (el) el.textContent = `${photos.length} / ${MAX_PHOTOS}`;

            const shutter = document.getElementById('btnShutter');
            if (shutter) shutter.disabled = photos.length >= MAX_PHOTOS;
        }

        /**
         * Render strip thumbnail 3 foto terakhir di dalam modal kamera.
         */
        function renderCamThumbs() {
            const strip = document.getElementById('camThumbStrip');
            if (!strip) return;
            strip.innerHTML = '';

            const recent = photos.slice(-3);
            recent.forEach(p => {
                const img = document.createElement('img');
                img.src = p.dataUrl;
                img.className = 'cam-thumb';
                strip.appendChild(img);
            });

            // Isi sisa dengan slot kosong hingga 3 slot
            const emptyCount = Math.max(0, 3 - recent.length);
            for (let i = 0; i < emptyCount; i++) {
                const empty = document.createElement('div');
                empty.className = 'cam-thumb-empty';
                strip.appendChild(empty);
            }
        }

        /**
         * Efek flash putih sebentar saat foto diambil.
         */
        function triggerFlash() {
            const flash = document.getElementById('cameraFlash');
            if (!flash) return;
            flash.classList.add('flash');
            setTimeout(() => flash.classList.remove('flash'), 150);
        }

        // Cleanup stream saat modal ditutup (tombol X atau backdrop)
        document.getElementById('cameraModal')?.addEventListener('hidden.bs.modal', () => {
            stopStream();
            renderPhotoGrid();
        });

        /* ══════════════════════════════════════════════════════════
           ANONYMOUS TOGGLE
        ══════════════════════════════════════════════════════════ */

        /**
         * Toggle mode anonim: sembunyikan/tampilkan field identitas.
         */
        function toggleAnon(checkbox) {
            const wrap = document.getElementById('anonWrap');
            const namaField = document.getElementById('namaField');
            const icon = document.getElementById('anonIcon');

            if (checkbox.checked) {
                wrap.classList.add('on');
                namaField.classList.add('hidden');
                icon.className = 'bi bi-incognito';
                // Kosongkan & nonaktifkan field agar tidak ikut tervalidasi
                document.getElementById('inputNama').value = '';
                document.getElementById('inputNama').disabled = true;
                document.getElementById('inputTelp').disabled = true;
            } else {
                wrap.classList.remove('on');
                namaField.classList.remove('hidden');
                document.getElementById('inputNama').disabled = false;
                document.getElementById('inputTelp').disabled = false;
            }
        }


        /* ══════════════════════════════════════════════════════════
           CHAR COUNTER
        ══════════════════════════════════════════════════════════ */

        /**
         * Update tampilan penghitung karakter secara real-time.
         * @param {HTMLElement} el    - input/textarea
         * @param {string}      spanId - id elemen <span> penampung angka
         */
        function updateChar(el, spanId) {
            const span = document.getElementById(spanId);
            if (span) span.textContent = el.value.length;
        }


        /* ══════════════════════════════════════════════════════════
           VALIDASI & SUBMIT FORM
        ══════════════════════════════════════════════════════════ */

        /**
         * Validasi semua field sebelum submit.
         * Kembalikan true jika valid, false jika ada yang kosong.
         */
        function validateForm() {
            const rules = [{
                    id: 'inputJudul',
                    msg: 'Judul laporan wajib diisi.',
                    check: el => el.value.trim().length > 0,
                },
                {
                    id: 'inputDesc',
                    msg: 'Deskripsi laporan wajib diisi.',
                    check: el => el.value.trim().length > 0,
                },
                {
                    id: 'inputLokasi',
                    msg: 'Lokasi wajib diisi.',
                    check: el => el.value.trim().length > 0,
                },
            ];

            // Validasi nama hanya jika tidak anonim
            const isAnon = document.getElementById('anonToggle')?.checked;
            if (!isAnon) {
                rules.push({
                    id: 'inputNama',
                    msg: 'Nama lengkap wajib diisi, atau aktifkan mode anonim.',
                    check: el => el.value.trim().length > 0,
                });
            }

            for (const rule of rules) {
                const el = document.getElementById(rule.id);
                if (!el) continue;
                if (!rule.check(el)) {
                    highlightError(el, rule.msg);
                    return false;
                }
            }
            return true;
        }

        /**
         * Submit form.
         * Validasi form, populate file input dengan photos, lalu submit form natively.
         */
        function submitForm() {
            if (!validateForm()) return;

            const btn = document.querySelector('.btn-submit');
            const form = document.getElementById('formLaporan');
            const fileInput = document.getElementById('fileInput');
            const deletedContainer = document.getElementById('deletedImagesContainer');

            if (!btn || !form || !fileInput) return;

            // Clear previous deleted inputs
            deletedContainer.innerHTML = '';

            // Add hidden inputs for deleted images
            deletedImages.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'deleted_images[]';
                input.value = id;
                deletedContainer.appendChild(input);
            });

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengirim...';

            try {
                // Buat DataTransfer untuk NEW photos only
                const dataTransfer = new DataTransfer();

                photos.forEach((p) => {
                    if (p.file) {
                        dataTransfer.items.add(p.file);
                    } else {
                        const blob = dataUrlToBlob(p.dataUrl);
                        const file = new File([blob], `kamera_${Date.now()}.jpg`, {
                            type: 'image/jpeg'
                        });
                        dataTransfer.items.add(file);
                    }
                });

                fileInput.files = dataTransfer.files;
                form.submit();

            } catch (error) {
                console.error('Submit error:', error);
                showToast(`Gagal mengirim: ${error.message}`, 'error');
                resetSubmitBtn(btn);
            }
        }

        /**
         * Bangun FormData dari semua field + foto untuk dikirim ke Laravel.
         * Panggil ini di dalam submitForm() saat menggunakan fetch().
         */
        function buildFormData() {
            const isAnon = document.getElementById('anonToggle')?.checked;
            const fd = new FormData();

            fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content ?? '');
            fd.append('judul', document.getElementById('inputJudul')?.value ?? '');
            fd.append('deskripsi', document.getElementById('inputDesc')?.value ?? '');
            fd.append('lokasi', document.getElementById('inputLokasi')?.value ?? '');
            fd.append('anonim', isAnon ? '1' : '0');

            if (!isAnon) {
                fd.append('nama', document.getElementById('inputNama')?.value ?? '');
                fd.append('telepon', document.getElementById('inputTelp')?.value ?? '');
            }

            // Foto: kirim sebagai base64 string (alternatif: konversi ke Blob)
            photos.forEach((p, i) => {
                if (p.file) {
                    // Foto dari file picker → kirim File asli
                    fd.append(`foto[${i}]`, p.file);
                } else {
                    // Foto dari kamera → konversi dataUrl ke Blob lalu append
                    const blob = dataUrlToBlob(p.dataUrl);
                    fd.append(`foto[${i}]`, blob, `kamera_${i + 1}.jpg`);
                }
            });

            return fd;
        }

        /**
         * Konversi base64 dataURL ke Blob (untuk foto dari kamera).
         */
        function dataUrlToBlob(dataUrl) {
            const [header, base64] = dataUrl.split(',');
            const mime = header.match(/:(.*?);/)[1];
            const bytes = atob(base64);
            const arr = new Uint8Array(bytes.length);
            for (let i = 0; i < bytes.length; i++) arr[i] = bytes.charCodeAt(i);
            return new Blob([arr], {
                type: mime
            });
        }

        function resetSubmitBtn(btn) {
            btn.disabled = false;
            btn.style.background = '';
            btn.innerHTML = '<i class="bi bi-send-fill"></i> Kirim Laporan';
        }


        /* ══════════════════════════════════════════════════════════
           UTILITIES
        ══════════════════════════════════════════════════════════ */

        /**
         * Highlight input yang error + tampilkan pesan di bawahnya.
         */
        function highlightError(el, msg) {
            el.classList.add('is-invalid');
            el.focus();
            el.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Hapus pesan error lama jika ada
            const oldTip = el.parentNode.querySelector('.err-tip');
            if (oldTip) oldTip.remove();

            const tip = document.createElement('div');
            tip.className = 'err-tip';
            tip.style.cssText = 'font-size:.74rem;color:var(--red-vivid);margin-top:4px;';
            tip.textContent = msg;
            el.parentNode.insertBefore(tip, el.nextSibling);

            // Auto-hilang setelah 3 detik
            setTimeout(() => {
                el.classList.remove('is-invalid');
                tip.remove();
            }, 3000);

            // Hilangkan error saat user mulai mengetik
            el.addEventListener('input', () => {
                el.classList.remove('is-invalid');
                tip.remove();
            }, {
                once: true
            });
        }

        /**
         * Toast notifikasi ringan (tanpa library tambahan).
         * @param {string} msg
         * @param {'success'|'error'|'warning'} type
         */
        function showToast(msg, type = 'success') {
            const colors = {
                success: {
                    bg: '#d1fae5',k
                    color: '#065f46',
                    icon: 'bi-check-circle-fill'
                },
                error: {
                    bg: '#fee2e2',
                    color: '#991b1b',
                    icon: 'bi-exclamation-circle-fill'
                },
                warning: {
                    bg: '#fef3c7',
                    color: '#92400e',
                    icon: 'bi-exclamation-triangle-fill'
                },
            };
            const c = colors[type] ?? colors.success;

            const toast = document.createElement('div');
            toast.style.cssText = `
    position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    background: ${c.bg}; color: ${c.color};
    padding: 12px 18px; border-radius: 12px;
    font-size: .86rem; font-weight: 500;
    display: flex; align-items: center; gap: 9px;
    box-shadow: 0 6px 24px rgba(0,0,0,.1);
    animation: fadeUp .3s ease;
    max-width: 320px;
  `;
            toast.innerHTML = `<i class="bi ${c.icon}"></i> ${msg}`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3500);
        }

        /**
         * Helper: set style properties pada elemen berdasarkan id.
         */
        function setEl(id, styles) {
            const el = document.getElementById(id);
            if (!el) return;
            Object.assign(el.style, styles);
        }


        /* ══════════════════════════════════════════════════════════
           INIT
        ══════════════════════════════════════════════════════════ */
        document.addEventListener('DOMContentLoaded', () => {
            updateTotalCount();
            renderPhotoGrid();
        });
    </script>
@endpush
