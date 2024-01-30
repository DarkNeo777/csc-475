<?php
try  {
    require_once("project/connection.php");

    if (isset($_POST['search'])) {
        // Search logic for vaccination sites
        $sql = "SELECT * FROM vaccinationSite WHERE siteName LIKE :siteName";
        $searchTerm = "%" . $_POST['siteName'] . "%";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':siteName', $searchTerm, PDO::PARAM_STR);
    } else {
        // Default: display all vaccination sites
        $sql = "SELECT * FROM vaccinationSite";
        $statement = $connection->prepare($sql);
    }

    $statement->execute();
    $sites = $statement->fetchAll();
} catch(PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    try {
        require_once("project/connection.php");

        $siteName = $_POST['siteName'];  // Get the site name from the form

        // Prepare SQL statement for deletion
        $sql = "DELETE FROM vaccinationSite WHERE siteName = :siteName";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':siteName', $siteName, PDO::PARAM_STR);

        $statement->execute();
        echo "Vaccination site successfully deleted.";

        // Redirect back to the same page to see the updated list
        header("Location: ".$_SERVER['PHP_SELF']);
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
    <title>Vaccination Sites List</title>
</head>
<body>
    <ul>
        <li><a href="index.php"><strong>Index</strong></a> - Homepage</li>
        <li><a href="view_vaccination_sites.php"><strong>page</strong></a></li>

    </ul>

    <h2>Search for a Vaccination Site</h2>
    <form method="post">
        <label for="siteName">Site Name:</label>
        <input type="text" id="siteName" name="siteName">
        <input type="submit" name="search" value="Search">
    </form>

    <h2>Vaccination Sites List</h2>
    <h4><a href="insert_site.php"><strong>Insert Site</strong></a></h4>
    <?php
    if ($sites && $statement->rowCount() > 0) { ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Site Name</th>
                    <th>Street Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip Code</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sites as $site) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($site["siteName"]); ?></td>
                        <td><?php echo htmlspecialchars($site["addrStreet"]); ?></td>
                        <td><?php echo htmlspecialchars($site["addrCity"]); ?></td>
                        <td><?php echo htmlspecialchars($site["addrState"]); ?></td>
                        <td><?php echo htmlspecialchars($site["addrZip"]); ?></td>
                        <td><a href='edit_site.php?siteName=<?php echo urlencode($site["siteName"]); ?>'>Edit</a></td>
                        <td>
    <form method="post">
        <input type="hidden" name="siteName" value="<?php echo htmlspecialchars($site["siteName"]); ?>">
        <input type="submit" name="delete" value="Delete">
    </form>
</td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No vaccination sites found.</p>
    <?php }
    ?>
</body>
</html>
