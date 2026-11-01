/**
 * ahp-3d.js - Modul Visualisasi Diagram Batang 3D Interaktif WebGL (Three.js)
 * untuk Sistem Pendukung Keputusan (SPK) Metode AHP.
 *
 * Fitur:
 * - Scene, PerspectiveCamera, WebGLRenderer dengan antialiasing & shadow support.
 * - OrbitControls untuk rotasi, zoom, dan pan kamera secara interaktif.
 * - 3D Bar Chart dengan material PBR (MeshStandardMaterial), bayangan, dan grid lantai.
 * - Text Sprite 3D dinamis yang melayang di atas setiap batang menampilkan nama & skor.
 * - Animasi reskalasi mulus (Smooth LERP Transition via requestAnimationFrame)
 *   merespons perubahan slider perbandingan secara real-time.
 */

(function () {
    'use strict';

    let scene, camera, renderer, controls;
    let canvas, container;
    let bars = [];
    let isInitialized = false;

    // Palet warna modern untuk batang 3D
    const PALETTE = [
        0x6366f1, // Indigo / Brand (Rank 1 default)
        0x06b6d4, // Cyan
        0x10b981, // Emerald
        0xf59e0b, // Amber
        0xec4899, // Pink
        0x8b5cf6, // Violet
        0x3b82f6, // Blue
        0x14b8a6, // Teal
    ];

    const CONFIG = {
        maxBarHeight: 7.5,
        barWidth: 1.2,
        barDepth: 1.2,
        spacing: 2.2,
        lerpSpeed: 0.08,
    };

    /**
     * Inisialisasi utama Three.js WebGL Engine.
     */
    function init() {
        canvas = document.getElementById('ahp3DCanvas');
        if (!canvas) {
            console.warn('[AHP-3D] Canvas #ahp3DCanvas tidak ditemukan.');
            return;
        }

        container = canvas.parentElement;

        // 1. Scene
        scene = new THREE.Scene();
        scene.background = new THREE.Color(0x0b0f19); // Slate-950 modern
        scene.fog = new THREE.FogExp2(0x0b0f19, 0.025);

        // 2. Camera
        const aspect = canvas.clientWidth / canvas.clientHeight;
        camera = new THREE.PerspectiveCamera(45, aspect, 0.1, 1000);
        camera.position.set(0, 7.5, 13);

        // 3. Renderer
        renderer = new THREE.WebGLRenderer({
            canvas: canvas,
            antialias: true,
            alpha: true,
            powerPreference: 'high-performance',
        });
        renderer.setSize(canvas.clientWidth, canvas.clientHeight, false);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;

        // 4. OrbitControls
        if (typeof THREE.OrbitControls !== 'undefined') {
            controls = new THREE.OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.dampingFactor = 0.05;
            controls.maxPolarAngle = Math.PI / 2 - 0.02; // Tidak tembus lantai
            controls.minDistance = 4;
            controls.maxDistance = 28;
            controls.target.set(0, 2.5, 0);
        } else {
            console.warn('[AHP-3D] OrbitControls tidak terdeteksi, navigasi mouse dinonaktifkan.');
        }

        // 5. Pencahayaan (Lighting)
        setupLighting();

        // 6. Lantai & Grid Lingkungan
        setupEnvironment();

        // 7. Event Resize
        window.addEventListener('resize', onWindowResize);
        if (window.ResizeObserver) {
            const ro = new ResizeObserver(onWindowResize);
            ro.observe(container);
        }

        // 8. Event Listener Real-Time dari Fetch API Slider
        window.addEventListener('ahpDataUpdated', function (event) {
            if (event.detail && event.detail.alternatives) {
                updateBarsData(event.detail.alternatives);
            }
        });

        isInitialized = true;

        // Sembunyikan overlay placeholder
        const placeholder = document.getElementById('canvasPlaceholder');
        if (placeholder) {
            placeholder.style.opacity = '0';
            setTimeout(() => {
                placeholder.style.display = 'none';
            }, 300);
        }

        // Mulai Render Loop
        animate();
    }

    /**
     * Konfigurasi pencahayaan ambient & directional light dengan bayangan realistis.
     */
    function setupLighting() {
        // Cahaya sekeliling (Ambient)
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.65);
        scene.add(ambientLight);

        // Cahaya utama (Key Directional Light) dengan bayangan
        const mainLight = new THREE.DirectionalLight(0xffffff, 0.95);
        mainLight.position.set(8, 16, 10);
        mainLight.castShadow = true;
        mainLight.shadow.mapSize.width = 1024;
        mainLight.shadow.mapSize.height = 1024;
        mainLight.shadow.camera.near = 0.5;
        mainLight.shadow.camera.far = 40;
        mainLight.shadow.camera.left = -10;
        mainLight.shadow.camera.right = 10;
        mainLight.shadow.camera.top = 10;
        mainLight.shadow.camera.bottom = -10;
        mainLight.shadow.bias = -0.0005;
        scene.add(mainLight);

        // Cahaya pengisi berwarna kebiruan (Blue Fill Light) dari sisi sebaliknya
        const fillLight = new THREE.DirectionalLight(0x818cf8, 0.45);
        fillLight.position.set(-8, 8, -6);
        scene.add(fillLight);

        // Titik cahaya aksen di dekat dasar panggung
        const accentPointLight = new THREE.PointLight(0x4f46e5, 0.8, 15);
        accentPointLight.position.set(0, 0.5, 4);
        scene.add(accentPointLight);
    }

    /**
     * Membuat lantai panggung dan grid pembantu.
     */
    function setupEnvironment() {
        // Grid Helper
        const gridHelper = new THREE.GridHelper(18, 18, 0x4f46e5, 0x1e293b);
        gridHelper.position.y = 0.01;
        scene.add(gridHelper);

        // Ground Plane untuk menangkap bayangan
        const planeGeo = new THREE.PlaneGeometry(30, 30);
        const planeMat = new THREE.MeshStandardMaterial({
            color: 0x090d16,
            roughness: 0.85,
            metalness: 0.1,
        });
        const ground = new THREE.Mesh(planeGeo, planeMat);
        ground.rotation.x = -Math.PI / 2;
        ground.position.y = 0;
        ground.receiveShadow = true;
        scene.add(ground);
    }

    /**
     * Membuat tekstur sprite teks untuk nama dan skor persentase alternatif di atas batang.
     */
    function createTextSprite(text, subtext, colorHex) {
        const canvasText = document.createElement('canvas');
        canvasText.width = 512;
        canvasText.height = 256;
        const ctx = canvasText.getContext('2d');

        // Latar belakang kartu label semi-transparan
        ctx.fillStyle = 'rgba(15, 23, 42, 0.85)';
        roundRect(ctx, 16, 16, 480, 224, 28);
        ctx.fill();

        // Border aksen
        ctx.strokeStyle = colorHex || '#6366f1';
        ctx.lineWidth = 6;
        roundRect(ctx, 16, 16, 480, 224, 28);
        ctx.stroke();

        // Teks Judul (Nama Alternatif)
        ctx.font = 'bold 44px "Plus Jakarta Sans", sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.textAlign = 'center';
        ctx.fillText(text, 256, 100);

        // Teks Skor Persentase
        ctx.font = '800 58px "Outfit", sans-serif';
        ctx.fillStyle = colorHex || '#a5b4fc';
        ctx.fillText(subtext, 256, 180);

        const texture = new THREE.CanvasTexture(canvasText);
        texture.minFilter = THREE.LinearFilter;
        texture.needsUpdate = true;

        const spriteMat = new THREE.SpriteMaterial({
            map: texture,
            transparent: true,
            depthTest: false,
        });
        const sprite = new THREE.Sprite(spriteMat);
        sprite.scale.set(2.4, 1.2, 1);
        return { sprite, texture, canvas: canvasText };
    }

    function roundRect(ctx, x, y, width, height, radius) {
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height - radius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
        ctx.lineTo(x + radius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);
        ctx.closePath();
    }

    /**
     * Memperbarui atau merekonstruksi batang 3D saat data kalkulasi AHP baru diterima.
     * Menggunakan interpolasi LERP halus untuk animasi naik/turun batang.
     */
    function updateBarsData(alternatives) {
        if (!isInitialized || !Array.isArray(alternatives) || alternatives.length === 0) {
            return;
        }

        const count = alternatives.length;
        const totalSpan = (count - 1) * CONFIG.spacing;
        const startX = -totalSpan / 2;

        // Cari skor maksimum untuk penskalaan visual relatif
        let maxScore = 0;
        alternatives.forEach(a => {
            if (a.final_score > maxScore) maxScore = a.final_score;
        });
        if (maxScore <= 0) maxScore = 1;

        // Jika jumlah alternatif berubah, hapus batang lama dan bangun ulang
        if (bars.length !== count) {
            clearExistingBars();
            buildNewBars(alternatives, startX, maxScore);
            return;
        }

        // Jika jumlah alternatif sama, perbarui target ketinggian dan teks sprite secara mulus
        alternatives.forEach((alt, idx) => {
            const barObj = bars[idx];
            const score = parseFloat(alt.final_score) || 0;
            const targetH = Math.max(0.2, (score / maxScore) * CONFIG.maxBarHeight);

            barObj.targetHeight = targetH;
            barObj.score = score;
            barObj.name = alt.name;
            barObj.rank = alt.rank || (idx + 1);

            // Perbarui tekstur sprite teks dengan skor baru
            const percentageStr = `${(score * 100).toFixed(1)}%`;
            updateSpriteTexture(barObj.spriteObj, alt.name, percentageStr, barObj.hexColorStr);
        });
    }

    /**
     * Membangun rangkaian objek batang 3D baru.
     */
    function buildNewBars(alternatives, startX, maxScore) {
        alternatives.forEach((alt, idx) => {
            const score = parseFloat(alt.final_score) || 0;
            const targetH = Math.max(0.2, (score / maxScore) * CONFIG.maxBarHeight);
            const posX = startX + (idx * CONFIG.spacing);
            const color = PALETTE[idx % PALETTE.length];
            const hexColorStr = '#' + color.toString(16).padStart(6, '0');

            // 1. Bar Mesh (Geometry Box 3D)
            const boxGeo = new THREE.BoxGeometry(CONFIG.barWidth, 1, CONFIG.barDepth);
            boxGeo.translate(0, 0.5, 0); // Pindahkan pivot ke dasar bawah batang

            const boxMat = new THREE.MeshStandardMaterial({
                color: color,
                roughness: 0.3,
                metalness: 0.25,
            });

            const barMesh = new THREE.Mesh(boxGeo, boxMat);
            barMesh.position.set(posX, 0, 0);
            barMesh.scale.set(1, 0.05, 1); // Awal tinggi mendekati 0 untuk animasi masuk
            barMesh.castShadow = true;
            barMesh.receiveShadow = true;
            scene.add(barMesh);

            // 2. Base Podium (Dudukan alas di bawah batang)
            const baseGeo = new THREE.CylinderGeometry(CONFIG.barWidth * 0.75, CONFIG.barWidth * 0.85, 0.15, 16);
            const baseMat = new THREE.MeshStandardMaterial({
                color: 0x1e293b,
                roughness: 0.6,
                metalness: 0.4,
            });
            const baseMesh = new THREE.Mesh(baseGeo, baseMat);
            baseMesh.position.set(posX, 0.08, 0);
            baseMesh.receiveShadow = true;
            scene.add(baseMesh);

            // 3. Floating 3D Text Sprite (Label Nama & Skor)
            const percentageStr = `${(score * 100).toFixed(1)}%`;
            const spriteObj = createTextSprite(alt.name, percentageStr, hexColorStr);
            spriteObj.sprite.position.set(posX, 1.2, 0);
            scene.add(spriteObj.sprite);

            bars.push({
                mesh: barMesh,
                baseMesh: baseMesh,
                spriteObj: spriteObj,
                currentHeight: 0.05,
                targetHeight: targetH,
                score: score,
                name: alt.name,
                rank: alt.rank || (idx + 1),
                color: color,
                hexColorStr: hexColorStr,
            });
        });

        // Sesuaikan target orbit controls agar fokus di tengah batang
        if (controls) {
            controls.target.set(0, CONFIG.maxBarHeight * 0.4, 0);
        }
    }

    /**
     * Memperbarui kanvas 2D yang menempel pada sprite 3D secara dinamis.
     */
    function updateSpriteTexture(spriteObj, text, subtext, colorHex) {
        if (!spriteObj || !spriteObj.canvas) return;

        const ctx = spriteObj.canvas.getContext('2d');
        ctx.clearRect(0, 0, 512, 256);

        ctx.fillStyle = 'rgba(15, 23, 42, 0.88)';
        roundRect(ctx, 16, 16, 480, 224, 28);
        ctx.fill();

        ctx.strokeStyle = colorHex || '#6366f1';
        ctx.lineWidth = 6;
        roundRect(ctx, 16, 16, 480, 224, 28);
        ctx.stroke();

        ctx.font = 'bold 44px "Plus Jakarta Sans", sans-serif';
        ctx.fillStyle = '#ffffff';
        ctx.textAlign = 'center';
        ctx.fillText(text, 256, 100);

        ctx.font = '800 58px "Outfit", sans-serif';
        ctx.fillStyle = colorHex || '#a5b4fc';
        ctx.fillText(subtext, 256, 180);

        spriteObj.texture.needsUpdate = true;
    }

    /**
     * Menghapus semua objek batang 3D saat inisialisasi ulang.
     */
    function clearExistingBars() {
        bars.forEach(b => {
            if (b.mesh) {
                scene.remove(b.mesh);
                b.mesh.geometry.dispose();
                b.mesh.material.dispose();
            }
            if (b.baseMesh) {
                scene.remove(b.baseMesh);
                b.baseMesh.geometry.dispose();
                b.baseMesh.material.dispose();
            }
            if (b.spriteObj && b.spriteObj.sprite) {
                scene.remove(b.spriteObj.sprite);
                b.spriteObj.texture.dispose();
                b.spriteObj.sprite.material.dispose();
            }
        });
        bars = [];
    }

    /**
     * Penyesuaian responsif ukuran kanvas saat jendela browser berubah.
     */
    function onWindowResize() {
        if (!canvas || !renderer || !camera) return;

        const width = canvas.clientWidth;
        const height = canvas.clientHeight;

        if (width === 0 || height === 0) return;

        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height, false);
    }

    /**
     * Render Loop & Smooth Animation (LERP).
     */
    function animate() {
        requestAnimationFrame(animate);

        // Update OrbitControls
        if (controls) {
            controls.update();
        }

        // Animasi transisi ketinggian batang (Linear Interpolation - LERP)
        for (let i = 0; i < bars.length; i++) {
            const b = bars[i];
            const diff = b.targetHeight - b.currentHeight;

            // Jika masih ada selisih ketinggian, interpolasikan
            if (Math.abs(diff) > 0.001) {
                b.currentHeight += diff * CONFIG.lerpSpeed;
                b.mesh.scale.y = Math.max(0.05, b.currentHeight);

                // Posisi sprite label mengikuti puncak ketinggian batang
                if (b.spriteObj && b.spriteObj.sprite) {
                    b.spriteObj.sprite.position.y = b.currentHeight + 0.85;
                }
            }
        }

        renderer.render(scene, camera);
    }

    // Eksekusi inisialisasi saat DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Ekspos ke global window untuk debugging atau trigger manual
    window.Ahp3D = {
        init: init,
        update: updateBarsData,
        getBars: () => bars,
    };
})();

