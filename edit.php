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

// Mendapatkan data dari ID yang dipilih
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tasks WHERE id=$id";
    $result = $conn->query($sql);
    $task_data = $result->fetch_assoc();
}

// Update (Edit data)
if (isset($_POST['task'])) {
    $id = $_POST['id'];
    $task = $_POST['task'];
    $start_date = $_POST['start'];
    $end_date = $_POST['end'];

    $sql = "UPDATE tasks SET task='$task', start_date='$start_date', end_date='$end_date' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: white;
            background-image: url('steve.jpg'); /* Menambahkan gambar latar belakang yang sama */
            background-size: cover;
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
            background-image: url('lebronsunshinecover.jpg'); /* Menggunakan gambar latar belakang yang sama pada form */
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
        input[type="submit"] {
            padding: 8px 16px;
            background-color: #ff4d4d; /* Menggunakan warna latar belakang yang sama */
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #e60000; /* Mengubah warna saat hover */
            transform: scale(1.05); /* Efek pembesaran saat hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Task</h2>

    <form action="edit.php" method="post">
        <input type="hidden" name="id" value="<?php echo $task_data['id']; ?>">
        <div>
            <label for="task">Task :</label>
            <input type="text" id="task" name="task" value="<?php echo $task_data['task']; ?>" required>
        </div>
        <div>
            <label for="start">Dimulai :</label>
            <input type="date" id="start" name="start" value="<?php echo $task_data['start_date']; ?>" required>
        </div>
        <div>
            <label for="end">Selesai :</label>
            <input type="date" id="end" name="end" value="<?php echo $task_data['end_date']; ?>" required>
        </div>
        <div>
            <input type="submit" value="Simpan Perubahan">
        </div>
    </form>
</div>

</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>



