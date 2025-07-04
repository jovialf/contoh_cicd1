<?php
$dataFile = 'data.json';

// Baca data dari file JSON
$jsonContent = file_exists($dataFile) ? file_get_contents($dataFile) : '';
$data = is_string($jsonContent) ? json_decode($jsonContent, true) : [];

if (!is_array($data)) {
    $data = [];
}

// Tambah data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = isset($_POST['nama']) && is_string($_POST['nama']) ? htmlspecialchars($_POST['nama']) : null;
    $hobby = isset($_POST['hobby']) && is_string($_POST['hobby']) ? htmlspecialchars($_POST['hobby']) : null;

    if ($nama !== null && $hobby !== null) {
        $data[] = [
            'nama' => $nama,
            'hobby' => $hobby
        ];
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
        header("Location: index.php");
        exit;
    }
}

// Hapus data
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $index = (int) $_GET['delete'];
    if (is_array($data) && isset($data[$index])) {
        unset($data[$index]);
        $data = array_values($data); // re-index array
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
    }
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Nama & Hobi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Input Nama & Hobi</h1>
        <form method="POST">
            <input type="text" name="nama" placeholder="Nama" required>
            <input type="text" name="hobby" placeholder="Hobi" required>
            <button type="submit">Simpan</button>
        </form>

        <h2>Data Tersimpan</h2>
        <?php if (empty($data)): ?>
            <p>Belum ada data.</p>
        <?php elseif (is_array($data)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Hobi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $i => $item): ?>
                        <?php
                        $nama = isset($item['nama']) && is_string($item['nama']) ? htmlspecialchars($item['nama']) : '';
                        $hobby = isset($item['hobby']) && is_string($item['hobby']) ? htmlspecialchars($item['hobby']) : '';
                        ?>
                        <tr>
                            <td><?= $nama ?></td>
                            <td><?= $hobby ?></td>
                            <td><a href="?delete=<?= $i ?>" onclick="return confirm('Yakin hapus?')">Hapus</a></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php endif ?>
    </div>
</body>
</html>
