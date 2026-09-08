<?php



require_once 'm_koneksi.php';

class HistoriTransaksi
{
    private $koneksi;

    public $id_histori;
    public $id_peminjaman;
    public $id_detail;

    public $nis;
    public $nama_siswa;

    public $id_buku;
    public $judul_buku;
    public $harga_buku;

    public $tanggal_pinjam;
    public $tanggal_pengembalian;
    public $tanggal_dikembalikan;

    public $jenis_denda;
    public $denda;

    public $tanggal_selesai;

    public function __construct()
    {
        $db = new Koneksi();
        $this->koneksi = $db->getKoneksi();
    }

    // =====================================================
    // MENAMPILKAN SEMUA HISTORI
    // =====================================================
    public function getAll()
    {
        $query = "
            SELECT *
            FROM histori_transaksi
            ORDER BY id_histori DESC
        ";

        $result = $this->koneksi->query($query);

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    // =====================================================
    // MENCARI HISTORI BERDASARKAN ID
    // =====================================================
    public function getById($id_histori)
    {
        $stmt = $this->koneksi->prepare("
            SELECT *
            FROM histori_transaksi
            WHERE id_histori = ?
        ");

        $stmt->bind_param(
            "i",
            $id_histori
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // =====================================================
    // HISTORI BERDASARKAN USER
    // =====================================================
    public function getByUser($nis)
    {
        $stmt = $this->koneksi->prepare("
            SELECT *
            FROM histori_transaksi
            WHERE nis = ?
            ORDER BY tanggal_selesai DESC
        ");

        $stmt->bind_param(
            "s",
            $nis
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    // =====================================================
    // HISTORI BERDASARKAN PEMINJAMAN
    // =====================================================
    public function getByPeminjaman($id_peminjaman)
    {
        $stmt = $this->koneksi->prepare("
            SELECT *
            FROM histori_transaksi
            WHERE id_peminjaman = ?
            ORDER BY id_histori ASC
        ");

        $stmt->bind_param(
            "i",
            $id_peminjaman
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    // =====================================================
    // TAMBAH HISTORI
    // =====================================================
    public function insert()
    {
        $stmt = $this->koneksi->prepare("
            INSERT INTO histori_transaksi
            (
                id_peminjaman,
                id_detail,
                nis,
                nama_siswa,
                id_buku,
                judul_buku,
                harga_buku,
                tanggal_pinjam,
                tanggal_pengembalian,
                tanggal_dikembalikan,
                jenis_denda,
                denda,
                tanggal_selesai
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iissisissssis",
            $this->id_peminjaman,
            $this->id_detail,
            $this->nis,
            $this->nama_siswa,
            $this->id_buku,
            $this->judul_buku,
            $this->harga_buku,
            $this->tanggal_pinjam,
            $this->tanggal_pengembalian,
            $this->tanggal_dikembalikan,
            $this->jenis_denda,
            $this->denda,
            $this->tanggal_selesai
        );

        return $stmt->execute();
    }

    // =====================================================
    // MENGHAPUS HISTORI
    // =====================================================
    public function delete($id_histori)
    {
        $stmt = $this->koneksi->prepare("
            DELETE FROM histori_transaksi
            WHERE id_histori = ?
        ");

        $stmt->bind_param(
            "i",
            $id_histori
        );

        return $stmt->execute();
    }

    // =====================================================
    // PENCARIAN HISTORI
    // =====================================================
    public function search($keyword)
    {
        $keyword = "%" . $keyword . "%";

        $stmt = $this->koneksi->prepare("
            SELECT *
            FROM histori_transaksi

            WHERE nis LIKE ?
               OR nama_siswa LIKE ?
               OR judul_buku LIKE ?

            ORDER BY tanggal_selesai DESC
        ");

        $stmt->bind_param(
            "sss",
            $keyword,
            $keyword,
            $keyword
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}













// require_once "m_koneksi.php";

// class m_histori{
//     private $koneksi;

//     public $id_histori;
//     public $id_peminjaman;
//     public $id_detail;
//     public $nis;
//     public $nama_siswa;
//     public $id_buku;
//     public $judul_buku;
//     public $harga_buku;
//     public $tanggal_pinjam;
//     public $tanggal_pengembaliaan; 
//     public $tanggal_dikembalikan;
//     public $jenis_denda;
//     public $denda;
//     public $tanggal_selesai; 


//     public function __construct()
//     {
//         $dbname = new koneksi();
//         $this->koneksi = $dbname->getkoneksi();
//     }

    
// }



?>