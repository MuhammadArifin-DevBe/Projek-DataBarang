<?php
require '../vendor/autoload.php';
require '../koneksi.php';

use Dompdf\Dompdf;

$query = mysqli_query($conn, "SELECT bk.*, mb.nama FROM barang_keluar bk JOIN master_barang mb ON bk.id_barang = mb.id ORDER BY bk.tanggal_pengajuan DESC");

$html = '<h2 style="text-align:center;">Laporan Pengajuan Barang</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
<tr>
    <th>No</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Pengambil</th>
    <th>Email</th>
    <th>Tanggal</th>
    <th>Status</th>
</tr>
</thead>
<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    $html .= '<tr>
        <td>'.$no++.'</td>
        <td>'.$row['nama'].'</td>
        <td>'.$row['jumlah'].'</td>
        <td>'.$row['nama_pengambil'].'</td>
        <td>'.$row['email_pengambil'].'</td>
        <td>'.$row['tanggal_pengajuan'].'</td>
        <td>'.$row['status_pengajuan'].'</td>
    </tr>';
}

$html .= '</tbody></table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_pengajuan.pdf", ["Attachment" => true]);
