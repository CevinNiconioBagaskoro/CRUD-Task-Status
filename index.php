<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'task_manager';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Buat variabel untuk pesan SweetAlert
$successMessage = "";

// Create (Insert data)
if (isset($_POST['task'])) {
    $task = $conn->real_escape_string($_POST['task']);
    $start_date = $conn->real_escape_string($_POST['start']);
    $end_date = $conn->real_escape_string($_POST['end']);

    $sql = "INSERT INTO tasks (task, start_date, end_date) VALUES ('$task', '$start_date', '$end_date')";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Data berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update status
if (isset($_GET['status_id'])) {
    $id = $conn->real_escape_string($_GET['status_id']);
    $status = $conn->real_escape_string($_GET['status']);

    $sql = "UPDATE tasks SET status='$status' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Status berhasil diperbarui!";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete (Hapus data)
if (isset($_GET['delete_id'])) {
    $id = $conn->real_escape_string($_GET['delete_id']);

    $sql = "DELETE FROM tasks WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Data berhasil dihapus!";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Filter Status
$statusFilter = "";
if (isset($_GET['status_filter'])) {
    $statusFilter = $conn->real_escape_string($_GET['status_filter']);
}

// Read (Menampilkan data berdasarkan filter)
$sql = "SELECT * FROM tasks";
if ($statusFilter && $statusFilter != 'Semua') {
    $sql .= " WHERE status='$statusFilter'";
}
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Dasbor Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: black;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            color: white;
            font-size: 90px;
        }

        form {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: rgba(249, 249, 249, 0.9);
            background-image: url('lebronsunshinecover.jpg');
            background-size: cover;
            background-position: center;
        }

        form div {
            margin-bottom: 10px;
        }

        label {
            display: inline-block;
            width: 100px;
        }

        input[type="text"], input[type="date"] {
            padding: 5px;
            width: 200px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid white;
        }

        th, td {
            padding: 10px;
            text-align: center;
            color: white;
        }

        a {
            color: white;
            text-decoration: none;
        }

        a:hover {
            color: blue;
        }

        .button-submit, .button-hapus {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 3s ease, color 3s ease, opacity 3s ease, transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .button-submit::after {
            content: "";
            background-image: url('titan.png');
            background-size: cover;
            background-position: center;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0;
            transition: opacity 3s ease;
            z-index: 1;
        }

        .button-submit:hover::after {
            opacity: 1;
        }

        .button-hapus::after {
            content: "";
            background-image: url('wle.png');
            background-size: cover;
            background-position: center;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0;
            transition: opacity 3s ease;
            z-index: 1;
        }

        .button-hapus:hover::after {
            opacity: 1;
        }

        .button-selesai {
            background-color: green;
            color: white;
            padding: 8px 16px;
            text-align: center;
            display: inline-block;
            border-radius: 5px;
            cursor: pointer;
        }

        .button-pending {
            background-color: red;
            color: white;
            padding: 8px 16px;
            text-align: center;
            display: inline-block;
            border-radius: 5px;
        }

    </style>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function applyFilter() {
            var statusFilter = document.getElementById('status_filter').value;
            window.location.href = 'index.php?status_filter=' + statusFilter;
        }
    </script>
</head>
<body>

<div class="container">
    <h2>HALAMAN DASBOR ADMIN</h2>

    <form action="index.php" method="post">
        <div>
            <label for="task">Task :</label>
            <input type="text" id="task" name="task" required>
        </div>
        <div>
            <label for="start">Dimulai :</label>
            <input type="date" id="start" name="start" required>
        </div>
        <div>
            <label for="end">Selesai :</label>
            <input type="date" id="end" name="end" required>
        </div>
        <div>
            <input type="submit" class="button-submit" value="Proses">
        </div>

    <!-- Filter berdasarkan status -->
    <label for="status_filter">Filter Status:</label>
    <select id="status_filter" name="status_filter" onchange="applyFilter()">
        <option value="Semua" <?php if ($statusFilter == 'Semua') echo 'selected'; ?>>Semua</option>
        <option value="Pending" <?php if ($statusFilter == 'Pending') echo 'selected'; ?>>Pending</option>
        <option value="Selesai" <?php if ($statusFilter == 'Selesai') echo 'selected'; ?>>Selesai</option>
    </select>


    </form>


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Task</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Hapus</th>
                <th>Ubah Status</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $i++ . "</td>
                            <td>" . $row['task'] . "</td>
                            <td>" . $row['start_date'] . "</td>
                            <td>" . $row['end_date'] . "</td>
                            <td>" . $row['status'] . "</td>
                            <td><a href='index.php?delete_id=" . $row['id'] . "' class='button-hapus'>Hapus</a></td>
                           <td>
                            <a href='index.php?status_id=" . $row['id'] . "&status=Selesai' class='button-selesai'>Selesai</a>
                            <a href='index.php?status_id=" . $row['id'] . "&status=Pending' class='button-pending'>Pending</a>
                            </td>
                            <td><a href='edit.php?id=" . $row['id'] . "'>Edit</a></td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Tidak ada data</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php if ($successMessage): ?>
      <!-- Script untuk menampilkan SweetAlert jika ada pesan sukses -->
<script>
<?php if (!empty($successMessage)) { ?>
    const gifHTML = '<img src="1011.gif" width="320" height="240" alt="Success GIF" />';

    Swal.fire({
        title: 'Success!',
        html: gifHTML + '<br>' + '<?php echo $successMessage; ?>', // Menambahkan GIF dan pesan
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php'; // Redirect setelah SweetAlert ditutup
        }
    });
<?php } ?>
</script>
    <?php endif; ?>
</div>

</body>
</html>
