<?php

require_once 'm_denda.php';

class DendaController
{
    private $denda;

    public function __construct()
    {
        $this->denda = new Denda();
    }


    // SEMUA DENDA

    public function index()
    {
        return $this->denda->getAll();
    }


    // DETAIL DENDA

    public function detail($id_denda)
    {
        return $this->denda->getById($id_denda);
    }


    // DENDA BERDASARKAN DETAIL PEMINJAMAN

    public function berdasarkanDetail($id_detail)
    {
        return $this->denda->getByDetail($id_detail);
    }


    // TAMBAH DENDA

    public function tambah(
        $id_detail,
        $jenis_denda,
        $jumlah_hari_terlambat,
        $jumlah_denda,
        $tanggal_denda,
        $status_pembayaran,
        $keterangan
    ) {
        $this->denda->id_detail = $id_detail;
        $this->denda->jenis_denda = $jenis_denda;
        $this->denda->jumlah_hari_terlambat =
            $jumlah_hari_terlambat;

        $this->denda->jumlah_denda =
            $jumlah_denda;

        $this->denda->tanggal_denda =
            $tanggal_denda;

        $this->denda->status_pembayaran =
            $status_pembayaran;

        $this->denda->keterangan =
            $keterangan;

        return $this->denda->insert();
    }


    // UPDATE DENDA

    public function update(
        $id_denda,
        $jenis_denda,
        $jumlah_hari_terlambat,
        $jumlah_denda,
        $tanggal_denda,
        $status_pembayaran,
        $keterangan
    ) {
        $this->denda->id_denda = $id_denda;
        $this->denda->jenis_denda = $jenis_denda;

        $this->denda->jumlah_hari_terlambat =
            $jumlah_hari_terlambat;

        $this->denda->jumlah_denda =
            $jumlah_denda;

        $this->denda->tanggal_denda =
            $tanggal_denda;

        $this->denda->status_pembayaran =
            $status_pembayaran;

        $this->denda->keterangan =
            $keterangan;

        return $this->denda->update();
    }


    // BAYAR DENDA

    public function bayar($id_denda)
    {
        return $this->denda->updateStatusPembayaran(
            $id_denda,
            'Sudah Dibayar'
        );
    }


    // HAPUS

    public function hapus($id_denda)
    {
        return $this->denda->delete($id_denda);
    }
}