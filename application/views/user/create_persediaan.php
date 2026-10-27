<div class="service-page">

    <!-- PILIHAN LAYANAN -->
    <div class="service-grid">
        <a href="<?= base_url('user_ticket/permintaan_persediaan') ?>"
           class="service-choice" aria-label="Permintaan Barang">
            <span class="service-choice-label">Permintaan Barang</span>
        </a>
        <a href="<?= base_url('user_ticket/pengajuan_persediaan') ?>"
           class="service-choice" aria-label="Pengajuan Barang">
            <span class="service-choice-label">Pengajuan Barang</span>
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
    align-items: flex-end;
    transition: background-color .25s ease;
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

/* gradasi gelap di bawah untuk label */
.service-choice::after {
    content: "";
    position: absolute;
    inset: 45% 0 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, .7));
    pointer-events: none;
}
.service-choice:hover,
.service-choice:focus-visible {
    background-color: rgba(255, 255, 255, .12);
}

.service-choice-label {
    position: relative;
    z-index: 1;
    margin: 1.5rem;
    color: #fff;
    font-size: 1.5rem;
    font-weight: 800;
    text-shadow: 0 2px 6px rgba(0, 0, 0, .45);
    transition: transform .2s ease;
}
.service-choice:hover .service-choice-label {
    transform: translateY(-4px);
}
</style>