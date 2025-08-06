<?php
$conn = new mysqli('localhost', 'root', '', 'db_barang');

if (!function_exists('registrasi')) {
    function registrasi($data)
    {
        global $conn;
        $username = strtolower(stripslashes($data["username"]));
        $password = mysqli_real_escape_string($conn, $data["password"]);

        // cek username
        $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
        if (mysqli_fetch_assoc($result)) {
            echo "<script>alert('Username sudah terdaftar!');</script>";
            return false;
        }

        // hash dan simpan
        $password = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        return mysqli_affected_rows($conn);
    }
}
