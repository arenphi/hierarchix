/**
 * csv-importer.js
 * Modul Khusus Impor, Validasi, dan Visualisasi Tabel Dataset CSV
 * 
 * CATATAN PENTING:
 * - DILARANG KERAS menyertakan logika / perhitungan AHP (Analytic Hierarchy Process) atau kode backend apa pun.
 * - Berjalan 100% Client-Side di browser (Pure Vanilla JavaScript).
 * - Fokus HANYA pada:
 *   1. Upload / drag-and-drop file CSV.
 *   2. Validasi ekstensi, ukuran, struktur baris-kolom, dan delimiter.
 *   3. Visualisasi data ke tabel HTML responsif dengan fitur pencarian dan download sample.
 */

(function () {
    'use strict';

    // Sample Dataset mentah sesuai instruksi pengguna
    const SAMPLE_CSV_RAW = `No,Nama Alternatif,C1_IPK,C2_Karya_Tulis_Ilmiah,C3_Prestasi,C4_Bahasa_Inggris
1,Sisilia,3.63,21.8,30.3,19.0
2,Sri Rizki,3.44,22.4,9.6,19.1
3,Fadilla,3.63,21.8,24.6,18.2
4,Saipul Badri,3.36,19.9,12.8,10.4
5,Almeera,3.85,24.3,13.8,22.5
6,Viola,3.48,21.2,11.6,17.31
7,Willy Trisno,3.64,22.4,21.3,17.7`;

    // State lokal dataset
    let activeDataset = null;

    // Helper formatting ukuran file
    function formatFileSize(bytes) {
        if (!bytes || bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Helper pengecekan nilai numerik
    function isNumeric(val) {
        if (typeof val !== 'string' && typeof val !== 'number') return false;
        const cleaned = String(val).trim().replace(',', '.');
        return !isNaN(cleaned) && !isNaN(parseFloat(cleaned)) && isFinite(cleaned);
    }

    // Parser CSV toleran terhadap tanda petik (quotes) dan pemisah koma / titik-koma
    function parseCsvLine(line, delimiter) {
        const result = [];
        let current = '';
        let inQuotes = false;

        for (let i = 0; i < line.length; i++) {
            const char = line[i];
            if (char === '"' || char === "'") {
                inQuotes = !inQuotes;
            } else if (char === delimiter && !inQuotes) {
                result.push(current.trim());
                current = '';
            } else {
                current += char;
            }
        }
        result.push(current.trim());
        return result;
    }

    // Deteksi otomatis delimiter (koma vs titik koma vs tab)
    function detectDelimiter(firstLine) {
        const commaCount = (firstLine.match(/,/g) || []).length;
        const semicolonCount = (firstLine.match(/;/g) || []).length;
        const tabCount = (firstLine.match(/\t/g) || []).length;

        if (semicolonCount > commaCount && semicolonCount > tabCount) return ';';
        if (tabCount > commaCount && tabCount > semicolonCount) return '\t';
        return ',';
    }

    // Validasi & Parsing Teks CSV
    function parseAndValidateCsv(csvText, fileName, fileSize) {
        if (!csvText || typeof csvText !== 'string' || csvText.trim().length === 0) {
            return {
                valid: false,
                error: 'File CSV kosong (0 bytes) atau tidak memiliki konten teks yang dapat dibaca.'
            };
        }

        // Pisahkan per baris (support CRLF dan LF)
        const rawLines = csvText.split(/\r?\n/).map(l => l.trim()).filter(l => l.length > 0);

        if (rawLines.length < 2) {
            return {
                valid: false,
                error: 'File CSV tidak memadai. Diperlukan minimal 1 baris header dan minimal 1 baris data alternatif.'
            };
        }

        const delimiter = detectDelimiter(rawLines[0]);
        const headers = parseCsvLine(rawLines[0], delimiter);

        if (headers.length < 2) {
            return {
                valid: false,
                error: `Header CSV hanya mendeteksi ${headers.length} kolom dengan delimiter "${delimiter}". Pastikan kolom dipisahkan koma (,) atau titik-koma (;).`
            };
        }

        const rows = [];
        const warnings = [];

        for (let i = 1; i < rawLines.length; i++) {
            const rowValues = parseCsvLine(rawLines[i], delimiter);
            if (rowValues.length !== headers.length) {
                warnings.push(`Baris ke-${i + 1} memiliki ${rowValues.length} kolom (diharapkan ${headers.length} kolom).`);
            }
            rows.push(rowValues);
        }

        // Tentukan tipe data tiap kolom berdasarkan data baris
        const columnTypes = headers.map((header, colIdx) => {
            let numericCount = 0;
            let totalChecked = 0;
            for (let r = 0; r < rows.length; r++) {
                if (rows[r][colIdx] !== undefined && rows[r][colIdx] !== '') {
                    totalChecked++;
                    if (isNumeric(rows[r][colIdx])) numericCount++;
                }
            }
            return (totalChecked > 0 && numericCount / totalChecked >= 0.7) ? 'numeric' : 'text';
        });

        return {
            valid: true,
            fileName: fileName || 'dataset.csv',
            fileSize: fileSize || new Blob([csvText]).size,
            delimiter: delimiter,
            headers: headers,
            rows: rows,
            columnTypes: columnTypes,
            warnings: warnings
        };
    }

    // Tampilkan pesan status validasi di UI
    function showValidationFeedback(dataset) {
        const box = document.getElementById('csvValidationBox');
        if (!box) return;

        box.classList.remove('hidden');

        if (!dataset.valid) {
            box.className = 'p-4 rounded-xl border border-rose-500/40 bg-rose-500/10 dark:bg-rose-950/20 text-rose-800 dark:text-rose-200 transition-all';
            box.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-lg bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-bold text-sm text-rose-900 dark:text-rose-200">Validasi File CSV Gagal</h4>
                        <p class="text-xs leading-relaxed text-rose-700 dark:text-rose-300">${dataset.error}</p>
                    </div>
                </div>
            `;
            return;
        }

        // Tampilan Valid Sukses
        box.className = 'p-4 rounded-xl border border-emerald-500/40 bg-emerald-500/10 dark:bg-emerald-950/20 text-emerald-900 dark:text-emerald-100 transition-all';
        
        let warningsHtml = '';
        if (dataset.warnings && dataset.warnings.length > 0) {
            warningsHtml = `
                <div class="mt-2.5 pt-2 border-t border-emerald-500/20 text-[11px] text-amber-700 dark:text-amber-300">
                    <span class="font-semibold">Catatan Peringatan:</span> ${dataset.warnings.slice(0, 3).join(' ')}
                </div>
            `;
        }

        const delimLabel = dataset.delimiter === ',' ? 'Koma (,)' : (dataset.delimiter === ';' ? 'Titik Koma (;)' : 'Tab (\\t)');

        box.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="space-y-1 flex-1">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <h4 class="font-bold text-sm text-emerald-900 dark:text-emerald-200 flex items-center gap-2">
                            <span>File CSV Valid & Siap Divisualisasikan</span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-800 dark:text-emerald-300">Format Sesuai</span>
                        </h4>
                        <span class="text-xs font-mono font-medium text-slate-500 dark:text-slate-400">${dataset.fileName} (${formatFileSize(dataset.fileSize)})</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 pt-1 text-xs text-slate-700 dark:text-slate-300">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                            <strong>Total Alternatif:</strong> ${dataset.rows.length} Baris
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                            <strong>Total Kolom:</strong> ${dataset.headers.length} Kolom
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                            <strong>Delimiter:</strong> ${delimLabel}
                        </span>
                    </div>
                    ${warningsHtml}
                </div>
            </div>
        `;
    }

    // Render Tabel Responsif
    function renderCsvTable(dataset, filterQuery = '') {
        const tableContainer = document.getElementById('csvTableContainer');
        const emptyState = document.getElementById('csvEmptyState');
        const tableWrapper = document.getElementById('csvTableWrapper');
        const thead = document.getElementById('csvTableHead');
        const tbody = document.getElementById('csvTableBody');
        const tableMeta = document.getElementById('csvTableMeta');
        const rowCounter = document.getElementById('csvRowCounter');

        if (!dataset || !dataset.valid) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (tableWrapper) tableWrapper.classList.add('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');
        if (tableWrapper) tableWrapper.classList.remove('hidden');

        // Filter baris berdasarkan pencarian
        const query = (filterQuery || '').toLowerCase().trim();
        const filteredRows = dataset.rows.filter(row => {
            if (!query) return true;
            return row.some(cell => String(cell).toLowerCase().includes(query));
        });

        // 1. Render Header Tabel
        let theadHtml = '<tr class="bg-slate-100/90 dark:bg-slate-800/90 text-slate-700 dark:text-slate-200 border-b border-slate-200 dark:border-slate-700 text-xs uppercase tracking-wider font-mono">';
        dataset.headers.forEach((header, idx) => {
            const isNum = dataset.columnTypes[idx] === 'numeric';
            const alignClass = idx === 0 ? 'text-center' : (isNum ? 'text-right' : 'text-left');
            const typeBadge = isNum 
                ? '<span class="ml-1 text-[9px] px-1.5 py-0.2 rounded bg-sky-500/15 text-sky-600 dark:text-sky-400 font-sans normal-case">angka</span>' 
                : '<span class="ml-1 text-[9px] px-1.5 py-0.2 rounded bg-slate-500/15 text-slate-600 dark:text-slate-400 font-sans normal-case">teks</span>';
            
            theadHtml += `
                <th scope="col" class="py-3 px-4 font-bold whitespace-nowrap ${alignClass}">
                    <div class="inline-flex items-center gap-1 justify-center">
                        <span>${escapeHtml(header)}</span>
                        ${typeBadge}
                    </div>
                </th>
            `;
        });
        theadHtml += '</tr>';
        if (thead) thead.innerHTML = theadHtml;

        // 2. Render Body Tabel
        let tbodyHtml = '';
        if (filteredRows.length === 0) {
            tbodyHtml = `
                <tr>
                    <td colspan="${dataset.headers.length}" class="py-8 text-center text-slate-400 dark:text-slate-500 text-sm italic">
                        Tidak ada baris data yang cocok dengan kata kunci pencarian "${escapeHtml(query)}".
                    </td>
                </tr>
            `;
        } else {
            filteredRows.forEach((row, rowIdx) => {
                tbodyHtml += `<tr class="border-b border-slate-100 dark:border-slate-800/60 transition-colors odd:bg-white even:bg-slate-50/60 dark:odd:bg-slate-900/40 dark:even:bg-slate-800/40 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/30">`;
                
                row.forEach((cellVal, colIdx) => {
                    const isNum = dataset.columnTypes[colIdx] === 'numeric';
                    const isFirstCol = colIdx === 0;

                    if (isFirstCol) {
                        tbodyHtml += `
                            <td class="py-2.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                    ${escapeHtml(cellVal)}
                                </span>
                            </td>
                        `;
                    } else if (isNum) {
                        tbodyHtml += `
                            <td class="py-2.5 px-4 text-right whitespace-nowrap font-mono text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200">
                                ${escapeHtml(cellVal)}
                            </td>
                        `;
                    } else {
                        tbodyHtml += `
                            <td class="py-2.5 px-4 text-left whitespace-nowrap font-medium text-xs sm:text-sm text-slate-900 dark:text-white">
                                ${escapeHtml(cellVal)}
                            </td>
                        `;
                    }
                });

                tbodyHtml += '</tr>';
            });
        }
        if (tbody) tbody.innerHTML = tbodyHtml;

        // 3. Update Status Counter & Meta
        if (rowCounter) {
            rowCounter.textContent = `Menampilkan ${filteredRows.length} dari ${dataset.rows.length} baris data`;
        }

        if (tableMeta) {
            tableMeta.innerHTML = dataset.headers.map((h, i) => `
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                    <span class="w-1.5 h-1.5 rounded-full ${dataset.columnTypes[i] === 'numeric' ? 'bg-sky-400' : 'bg-slate-400'}"></span>
                    ${escapeHtml(h)}
                </span>
            `).join('');
        }
    }

    // Helper sanitasi HTML sederhana untuk proteksi XSS
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Handle File Unggahan
    function handleFile(file) {
        if (!file) return;

        // Validasi nama / ekstensi
        const fileName = file.name || 'dataset.csv';
        const isCsv = fileName.toLowerCase().endsWith('.csv') || file.type === 'text/csv' || file.type === 'text/plain';

        if (!isCsv) {
            showValidationFeedback({
                valid: false,
                error: `Tipe file "${fileName}" tidak didukung. Harap unggah file dokumen berformat (.csv).`
            });
            renderCsvTable(null);
            return;
        }

        // Validasi ukuran
        if (file.size === 0) {
            showValidationFeedback({
                valid: false,
                error: `File "${fileName}" kosong (0 bytes). Pastikan file CSV memuat data tabular.`
            });
            renderCsvTable(null);
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            showValidationFeedback({
                valid: false,
                error: `Ukuran file "${fileName}" (${formatFileSize(file.size)}) melebihi batas maksimum 5 MB.`
            });
            renderCsvTable(null);
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const content = e.target.result;
            const parsed = parseAndValidateCsv(content, fileName, file.size);
            activeDataset = parsed;
            showValidationFeedback(parsed);
            renderCsvTable(parsed);
        };
        reader.onerror = function () {
            showValidationFeedback({
                valid: false,
                error: `Terjadi galat teknis saat membaca file "${fileName}" dari perangkat.`
            });
            renderCsvTable(null);
        };
        reader.readAsText(file);
    }

    // Load Sample CSV bawaan
    function loadSampleDataset() {
        const sampleText = SAMPLE_CSV_RAW;
        const parsed = parseAndValidateCsv(sampleText, 'dataset_contoh_mawapres.csv', new Blob([sampleText]).size);
        activeDataset = parsed;
        showValidationFeedback(parsed);
        renderCsvTable(parsed);

        // Reset search input
        const searchInput = document.getElementById('csvSearchInput');
        if (searchInput) searchInput.value = '';
    }

    // Unduh Sample CSV ke lokal
    function downloadSampleCsv() {
        const blob = new Blob([SAMPLE_CSV_RAW], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', 'sample_dataset_mawapres.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }

    // Reset / Bersihkan Tabel
    function clearCsvDataset() {
        activeDataset = null;
        const fileInput = document.getElementById('csvFileInput');
        if (fileInput) fileInput.value = '';

        const searchInput = document.getElementById('csvSearchInput');
        if (searchInput) searchInput.value = '';

        const box = document.getElementById('csvValidationBox');
        if (box) {
            box.classList.add('hidden');
            box.innerHTML = '';
        }

        renderCsvTable(null);
    }

    // Inisialisasi Event Listener
    function initCsvModule() {
        const dropZone = document.getElementById('csvDropZone');
        const fileInput = document.getElementById('csvFileInput');
        const browseBtn = document.getElementById('csvBrowseBtn');
        const loadSampleBtn = document.getElementById('csvLoadSampleBtn');
        const downloadSampleBtn = document.getElementById('csvDownloadSampleBtn');
        const clearBtn = document.getElementById('csvClearBtn');
        const searchInput = document.getElementById('csvSearchInput');

        if (!dropZone || !fileInput) return;

        // 1. Tombol Browse
        if (browseBtn) {
            browseBtn.addEventListener('click', () => fileInput.click());
        }

        // 2. Input File Change
        fileInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        // 3. Drag and Drop Handlers
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('border-indigo-500', 'bg-indigo-50/60', 'dark:bg-indigo-950/30', 'scale-[1.005]');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-indigo-500', 'bg-indigo-50/60', 'dark:bg-indigo-950/30', 'scale-[1.005]');
            });
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            if (dt && dt.files && dt.files.length > 0) {
                handleFile(dt.files[0]);
            }
        });

        // 4. Action Buttons
        if (loadSampleBtn) {
            loadSampleBtn.addEventListener('click', loadSampleDataset);
        }

        if (downloadSampleBtn) {
            downloadSampleBtn.addEventListener('click', downloadSampleCsv);
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', clearCsvDataset);
        }

        // 5. Live Search Input
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                if (activeDataset) {
                    renderCsvTable(activeDataset, e.target.value);
                }
            });
        }
    }

    // Jalankan saat DOM telah siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCsvModule);
    } else {
        initCsvModule();
    }

})();
