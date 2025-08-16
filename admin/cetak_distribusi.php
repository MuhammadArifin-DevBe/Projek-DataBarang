<?php
require '../vendor/autoload.php';
require '../koneksi.php';

use Dompdf\Dompdf;

$query = mysqli_query($conn, "
    SELECT bd.*, mb.nama 
    FROM barang_distribusi bd 
    JOIN master_barang mb ON bd.id_barang = mb.id 
    ORDER BY bd.tanggal_distribusi DESC
");

$html = '<h2 style="text-align:center;">Laporan Distribusi Barang</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
<tr>
    <th>No</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Tanggal</th>
</tr>
</thead>
<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    $html .= '<tr>
        <td>'.$no++.'</td>
        <td>'.$row['nama'].'</td>
        <td>'.$row['jumlah'].'</td>
        <td>'.$row['tanggal_distribusi'].'</td>
    </tr>';
}

$html .= '</tbody></table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_distribusi.pdf", ["Attachment" => true]);
