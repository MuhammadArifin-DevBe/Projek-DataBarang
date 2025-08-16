<?php
require '../vendor/autoload.php';
require '../koneksi.php';

use Dompdf\Dompdf;

$query = mysqli_query($conn, "
    SELECT la.*, u.username 
    FROM log_aktivitas la 
    JOIN users u ON la.user_id = u.id 
    ORDER BY la.waktu DESC
");

$html = '<h2 style="text-align:center;">Laporan Log Aktivitas</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
<tr>
    <th>No</th>
    <th>User</th>
    <th>Aktivitas</th>
    <th>Waktu</th>
</tr>
</thead>
<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    $html .= '<tr>
        <td>'.$no++.'</td>
        <td>'.$row['username'].'</td>
        <td>'.$row['aktivitas'].'</td>
        <td>'.$row['waktu'].'</td>
    </tr>';
}

$html .= '</tbody></table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_log.pdf", ["Attachment" => true]);
