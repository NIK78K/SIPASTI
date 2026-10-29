<div class="service-page">

    <!-- PILIHAN LAYANAN -->
    <div class="service-grid">
        <a href="<?= base_url('user_ticket/permintaan_persediaan') ?>"
           class="service-choice" aria-label="Permintaan Barang">
            <span class="service-choice-content">
                <strong class="service-choice-label">Permintaan Barang</strong>
                <span class="service-choice-description">Pilih barang yang tersedia di katalog persediaan</span>
                <span class="service-choice-action">Buka layanan</span>
            </span>
        </a>
        <a href="<?= base_url('user_ticket/pengajuan_persediaan') ?>"
           class="service-choice" aria-label="Pengajuan Barang">
            <span class="service-choice-content">
                <strong class="service-choice-label">Pengajuan Barang</strong>
                <span class="service-choice-description">Ajukan barang baru yang belum tersedia di katalog</span>
                <span class="service-choice-action">Buka layanan</span>
            </span>
        </a>
    </div>

</div>

<style>
.service-page {
    position: relative;
    width: 100%;
    height: calc(100dvh - 4rem);
    min-height: 0;
    overflow: hidden;
    background: url("<?= base_url('assets/img/pilihan-layanan-persediaan.png') ?>") center / cover no-repeat;
}

/* gradasi gelap di atas supaya judul terbaca */
.service-page::before {
    content: "";
    position: absolute;
    inset: 0 0 auto 0;
    height: 14rem;
    background: linear-gradient(rgba(0, 0, 0, .65), transparent);
    z-index: 1;
    pointer-events: none;
}

.service-grid {
    position: absolute;
    inset: 0;
    z-index: 2;
    display: grid;
    grid-template-rows: 1fr 1fr;      /* mobile: atas-bawah */
}
@media (min-width: 768px) {
    .service-grid {
        grid-template-rows: none;
        grid-template-columns: 1fr 1fr;   /* desktop: kiri-kanan */
    }
}

.service-choice {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    z-index: 0;
    transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease;
}
.service-choice + .service-choice {
    border-top: 1px solid rgba(255, 255, 255, .45);
}
@media (min-width: 768px) {
    .service-choice + .service-choice {
        border-top: 0;
        border-left: 1px solid rgba(255, 255, 255, .45);
    }
}

.service-choice::before {
    content: "";
    position: absolute;
    inset: -12px;
    z-index: 0;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    pointer-events: none;
}
.service-choice:first-child::before {
    background-image: url("<?= base_url('assets/img/permintaan_barang.jpg') ?>");
}
.service-choice:last-child::before {
    background-image: url("<?= base_url('assets/img/pengajuan_barang.png') ?>");
}

/* gradasi gelap di atas gambar agar label terbaca */
.service-choice::after {
    content: "";
    position: absolute;
    inset: 0;
    z-index: 1;
    background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .68));
    pointer-events: none;
}
.service-choice:hover,
.service-choice:focus-visible {
    z-index: 3;
}
.service-choice:hover::after,
.service-choice:focus-visible::after {
    background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .72));
}

.service-choice-label {
    display: block;
    color: #fff;
    font-size: 2rem;
    font-weight: 800;
    text-shadow: 0 2px 6px rgba(0, 0, 0, .45);
}
.service-choice-content {
    position: relative;
    z-index: 2;
    display: flex;
    width: min(80%, 28rem);
    flex-direction: column;
    align-items: center;
    gap: .75rem;
    color: #fff;
    text-align: center;
    text-shadow: 0 2px 6px rgba(0, 0, 0, .45);
}
.service-choice-description {
    max-width: 24rem;
    font-size: .95rem;
    line-height: 1.5;
}
.service-choice-action {
    padding: .45rem 1rem;
    border: 1px solid rgba(255, 255, 255, .8);
    border-radius: 999px;
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
}
@media (max-width: 767px) {
    .service-choice-label {
        font-size: 1.5rem;
    }
}
</style>