<?php
try  {
    require_once("project/connection.php");

    if (isset($_POST['search'])) {
        // Search logic
        $sql = "SELECT * FROM vaccine WHERE scientificName LIKE :scientificName";
        $searchTerm = "%" . $_POST['searchScientificName'] . "%";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':scientificName', $searchTerm, PDO::PARAM_STR);

    } else {
        // Default: display all vaccines
        $sql = "SELECT * FROM vaccine";
        $statement = $connection->prepare($sql);
    }
    

    $statement->execute();
    $vaccines = $statement->fetchAll();
} catch(PDOException $error) {
    echo "Error: " . $error->getMessage();
}


?>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    try {
        require_once("project/connection.php");

        $scientificName = $_POST['scientificName'];
        $sql = "DELETE FROM vaccine WHERE scientificName = :scientificName";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':scientificName', $scientificName, PDO::PARAM_STR);

        $statement->execute();

        // Redirect to refresh the list
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch(PDOException $error) {
        echo "Error: " . $error->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vaccine List</title>
</head>
<body>
    <ul>
        <li><a href="index.php"><strong>Index</strong></a> - Homepage</li>
    </ul>

    <form method="post">
    <label for="searchScientificName">Search by Scientific Name:</label>
    <input type="text" id="searchScientificName" name="searchScientificName">
    <input type="submit" name="search" value="Search">
</form>

    <h2>Vaccine List</h2>

    <h4>
            <a href="insert_vaccine.php"><strong>Insert Vaccine</strong></a> 
</h4>
    <?php
    if ($vaccines && $statement->rowCount() > 0) { ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Scientific Name</th>
                    <th>Disease</th>
                    <th>Number of Doses</th>
                    <th>Delete</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vaccines as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["scientificName"]); ?></td>
                        <td><?php echo htmlspecialchars($row["disease"]); ?></td>
                        <td><?php echo htmlspecialchars($row["noDoses"]); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="scientificName" value="<?php echo htmlspecialchars($row["scientificName"]); ?>">
                                <input type="submit" name="delete" value="Delete">
                            </form>
                        </td>
                        <td>
                             <a href='edit_vaccine.php?scientificName=<?php echo htmlspecialchars($row["scientificName"]); ?>'>Edit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No vaccines found.</p>
    <?php }
    ?>
</body>
</html>
