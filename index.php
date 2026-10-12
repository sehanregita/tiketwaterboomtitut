
<?php
$harga_anak = 25000;
$harga_dewasa = 40000;
$hasil = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama"] ?? "");
    $jenis = $_POST["jenis"] ?? "";
    $jumlah = filter_var($_POST["jumlah"] ?? "", FILTER_VALIDATE_INT);
    $uang = filter_var($_POST["uang"] ?? "", FILTER_VALIDATE_INT);

    if (
        $nama === "" ||
        !in_array($jenis, ["Anak-anak", "Dewasa"], true) ||
        $jumlah === false || $jumlah < 1 ||
        $uang === false || $uang < 0
    ) {
        $error = "Mohon isi semua data dengan benar.";
    } else {
        $harga = ($jenis == "Anak-anak")
            ? $harga_anak
            : $harga_dewasa;

        $total = $harga * $jumlah;

        $hasil = [
            "nama" => $nama,
            "jenis" => $jenis,
            "jumlah" => $jumlah,
            "harga" => $harga,
            "total" => $total,
            "uang" => $uang,
            "kembalian" => $uang - $total
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Tiket Waterboom</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #e8f7ff;
            color: #123047;
        }

        header {
            background: #008cba;
            color: white;
            text-align: center;
            padding: 25px 15px;
        }

        header h1 {
            margin: 0 0 10px;
        }

        header p {
            margin: 0;
        }

        .container {
            max-width: 550px;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px #00000015;
        }

        h2 {
            text-align: center;
            color: #008cba;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #b9dce9;
            border-radius: 7px;
            font-size: 15px;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 13px;
            border: none;
            border-radius: 7px;
            background: #008cba;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #006b91;
        }

        .hasil {
            margin-top: 25px;
            padding: 18px;
            background: #e5f8ed;
            border-radius: 10px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .error {
            padding: 12px;
            background: #ffe8e8;
            color: #a00000;
            border-radius: 7px;
        }

        .harga {
            background: #f0faff;
            padding: 12px;
            border-radius: 8px;
            line-height: 1.8;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #008cba;
            color: white;
            margin-top: 35px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px 12px;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>🌊 WATERBOOM CERIA 🌊</h1>
    <p>Segarkan Harimu dengan Bermain Air!</p>
</header>

<div class="container">
    <h2>Form Pembelian Tiket</h2>

    <div class="harga">
        <strong>Daftar Harga Tiket</strong><br>
        Anak-anak: Rp25.000<br>
        Dewasa: Rp40.000
    </div>

    <form method="POST" action="">
        <label>Nama Pembeli</label>
        <input
            type="text"
            name="nama"
            placeholder="Masukkan nama Anda"
            maxlength="100"
            required
        >

        <label>Jenis Tiket</label>
        <select name="jenis" required>
            <option value="Anak-anak">Anak-anak - Rp25.000</option>
            <option value="Dewasa">Dewasa - Rp40.000</option>
        </select>

        <label>Jumlah Tiket</label>
        <input
            type="number"
            name="jumlah"
            min="1"
            max="100"
            placeholder="Masukkan jumlah tiket"
            required
        >

        <label>Uang Pembayaran (Rp)</label>
        <input
            type="number"
            name="uang"
            min="0"
            placeholder="Masukkan uang pembayaran"
            required
        >

        <button type="submit">Beli Tiket Sekarang</button>
    </form>

    <?php if ($error !== ""): ?>
        <div class="hasil error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($hasil !== null): ?>
        <div class="hasil">
            <h2>Detail Pembelian</h2>

            Nama: <?= htmlspecialchars($hasil["nama"]) ?><br>
            Jenis Tiket: <?= htmlspecialchars($hasil["jenis"]) ?><br>
            Jumlah Tiket: <?= $hasil["jumlah"] ?><br>

            Harga Tiket:
            Rp<?= number_format($hasil["harga"], 0, ',', '.') ?><br>

            <strong>
                Total:
                Rp<?= number_format($hasil["total"], 0, ',', '.') ?>
            </strong><br>

            Uang Pembayaran:
            Rp<?= number_format($hasil["uang"], 0, ',', '.') ?><br>

            <?php if ($hasil["kembalian"] >= 0): ?>
                Kembalian:
                Rp<?= number_format($hasil["kembalian"], 0, ',', '.') ?>
                <br>
                <strong>Status: Pembelian berhasil!</strong>
            <?php else: ?>
                Kekurangan:
                Rp<?= number_format(abs($hasil["kembalian"]), 0, ',', '.') ?>
                <br>
                <strong>Status: Uang pembayaran belum cukup.</strong>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; <?= date("Y") ?> Waterboom Ceria</p>
    <p>Terima kasih telah berkunjung!</p>
</footer>

</body>
</html>
