<?php
error_reporting(1);
include('connection.php');

if (isset($_GET['delete'])) {
    $hotelId = $_GET['delete'];
    
    
    $deleteQuery = "DELETE FROM hotels WHERE id = '$hotelId'";
    mysqli_query($con, $deleteQuery);
    

    $deleteImagesQuery = "DELETE FROM images WHERE hotel_id = '$hotelId'";
    mysqli_query($con, $deleteImagesQuery);
    
    header("Location: list.php");
    exit();
}

$query = "SELECT * FROM hotels";
$result = mysqli_query($con, $query);
$hotels = [];
while ($row = mysqli_fetch_assoc($result)) {
    $hotelId = $row['id'];
    $query = "SELECT path FROM images WHERE hotel_id = '$hotelId'";
    $imageResult = mysqli_query($con, $query);
    $images = [];
    while ($imageRow = mysqli_fetch_assoc($imageResult)) {
        $images[] = $imageRow['path'];
    }
    $row['images'] = $images;
    $hotels[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
<title>Hotel Listing</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
    color: #fff;
    background: #63738a;
    font-family: 'Roboto', sans-serif;
}

.container {
    max-width: 960px;
    margin: 0 auto;
    padding: 30px;
}

.table {
    background-color: #f2f3f7;
    border: none;
    border-radius: 3px;
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    padding: 20px;
    margin-bottom: 30px;
}

.table-header {
    background-color: #5cb85c;
    color: #fff;
    padding: 10px;
    margin-bottom: 10px;
}

.table-body {
    margin-bottom: 10px;
}

.table-title {
    font-size: 24px;
    margin-bottom: 10px;
}

.table-description {
    margin-bottom: 10px;
}

.table-facilities {
    margin-bottom: 10px;
}

.table-facilities ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.table-facilities li {
    display: inline-block;
    margin-right: 10px;
}

.btn {
    margin-right: 10px;
}

</style>
</head>
<body>
<div class="container">
    <h2>Hotel Listing</h2>
    <table class="table">
        <thead class="table-header">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Facilities</th>
                <th>Status</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-body">
            <?php foreach ($hotels as $hotel) { ?>
                <tr>
                    <td><?php echo $hotel['id']; ?></td>
                    <td><?php echo $hotel['name']; ?></td>
                    <td><?php echo $hotel['type']; ?></td>
                    <td><?php echo $hotel['description']; ?></td>
                    <td>
                        <?php $facilities = explode(',', $hotel['facility']); ?>
                        <?php foreach ($facilities as $facility) { ?>
                            <?php echo $facility; ?>
                        <?php } ?>
                    </td>
                    <td><?php echo ($hotel['status'] == 1) ? 'Available' : 'Not Available'; ?></td>
                    <td>
                        <?php if (!empty($hotel['images'])) { ?>
                            <ul>
                                <?php foreach ($hotel['images'] as $image) { ?>
                                    <li>
                                        <img src="<?php echo $image; ?>" alt="Hotel Image" width="50">
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="edit.php?edit=<?php echo $hotel['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="list.php?delete=<?php echo $hotel['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this hotel?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
