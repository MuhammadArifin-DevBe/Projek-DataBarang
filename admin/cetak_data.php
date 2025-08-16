<?php
require '../vendor/autoload.php';
require '../koneksi.php';

use Dompdf\Dompdf;

$query = mysqli_query($conn, "SELECT * FROM master_barang ORDER BY id DESC");

$html = '<h2 style="text-align:center;">Laporan Data Barang</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
<tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Kategori</th>
    <th>Jenis Barang</th>
    <th>Stok</th>
    <th>Satuan</th>
    <th>Harga Barang</th>
    <th>Tanggal Beli</th>
    <th>Diperoleh</th>
    <th>Kondisi</th>
    <th>Status</th>
</tr>
</thead>
<tbody>';

while ($row = mysqli_fetch_assoc($query)) {
    $html .= '<tr>
        <td>'.$row['id'].'</td>
        <td>'.$row['nama'].'</td>
        <td>'.$row['kategori_barang'].'</td>
        <td>'.$row['jenis_barang'].'</td>
        <td>'.$row['stok'].'</td>
        <td>'.$row['satuan'].'</td>
        <td>'.$row['harga_brg'].'</td>
        <td>'.$row['tgl_beli'].'</td>
        <td>'.$row['diprlh_dri'].'</td>
        <td>'.$row['kondisi'].'</td>
        <td>'.$row['status'].'</td>
    </tr>';
}

$html .= '</tbody></table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_barang.pdf", ["Attachment" => true]);
