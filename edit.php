<?php
error_reporting(1);
include('connection.php');


if (isset($_POST['submit'])) {
    $hotelId = $_POST['hotel_id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $facility = $_POST['facility'];
    $status = isset($_POST['status']) ? 1 : 0;

    $updateQuery = "UPDATE hotels SET name = '$name', type = '$type', description = '$description', facility = '$facility', status = '$status' WHERE id = '$hotelId'";
    mysqli_query($con, $updateQuery);

    $fileCount = count($_FILES['image']['name']);
    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = $_FILES['image']['name'][$i];
        $tmpFilePath = $_FILES['image']['tmp_name'][$i];

        if ($fileName != "") {
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $currentDateTime = date('Y-m-d_H-i-s');
            $newFileName = $currentDateTime . '_' . $i . '.' . $extension;
            $uploadFolder = 'images/' . $newFileName;
            move_uploaded_file($tmpFilePath, $uploadFolder);

            $query = "INSERT INTO images (hotel_id, path) VALUES ('$hotelId', '$uploadFolder')";
            mysqli_query($con, $query);
        }
    }

    
    header("Location: list.php");
    exit();
}


$hotelId = $_GET['edit'];
$query = "SELECT * FROM hotels WHERE id = '$hotelId'";
$result = mysqli_query($con, $query);
$hotel = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <title>Edit Hotel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            color: #fff;
            background: #63738a;
            font-family: 'Roboto', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            font-size: 18px;
        }
        .form-control {
            height: 40px;
            font-size: 16px;
        }
        .btn {
            margin-right: 10px;
        }
        .image-preview {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .image-preview img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .table {
            margin-top: 20px;
            background-color: #fff;
            color: #000;
        }
        .table th {
            background-color: #f2f2f2;
            color: #000;
            font-weight: bold;
            padding: 10px;
        }
        .table td {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Hotel</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $hotel['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" name="type" id="type" class="form-control" value="<?php echo $hotel['type']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required><?php echo $hotel['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="facility">Facilities</label>
                <input type="text" name="facility" id="facility" class="form-control" value="<?php echo $hotel['facility']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <div class="form-check">
                    <input type="checkbox" name="status" id="status" class="form-check-input" <?php if ($hotel['status'] == 1) echo 'checked'; ?>>
                    <label class="form-check-label" for="status">Available</label>
                </div>
            </div>
            <div class="form-group">
                <label for="image">Images</label>
                <input type="file" class="form-control" name="image[]" multiple>
            </div>
            <div class="image-preview">
                <?php
                // Display insert images
                $imageQuery = "SELECT * FROM images WHERE hotel_id = '$hotelId'";
                $imageResult = mysqli_query($con, $imageQuery);
                while ($image = mysqli_fetch_assoc($imageResult)) {
                    echo '<img src="' . $image['path'] . '" alt="Image">';
                }
                ?>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Update</button>
            <!-- <a href="list.php" class="btn btn-secondary">Cancel</a> -->
        </form>
       
    </div>
</body>
</html
