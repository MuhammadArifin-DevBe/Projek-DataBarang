<?php
require '../vendor/autoload.php';
require '../koneksi.php';

use Dompdf\Dompdf;

$query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

$html = '<h2 style="text-align:center;">Laporan Data Pengguna</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
<tr>
    <th>No</th>
    <th>Username</th>
    <th>Email</th>
    <th>Sebagai</th>
</tr>
</thead>
<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    $html .= '<tr>
        <td>'.$no++.'</td>
        <td>'.$row['username'].'</td>
        <td>'.$row['email'].'</td>
        <td>'.$row['role'].'</td>
    </tr>';
}

$html .= '</tbody></table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_pengguna.pdf", ["Attachment" => true]);
