<?php
if (isset($_GET['siteName'])) {
    try {
        require_once("project/connection.php");

        $sql = "SELECT * FROM vaccinationSite WHERE siteName = :siteName";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':siteName', $_GET['siteName']);
        $statement->execute();

        $site = $statement->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $error) {
        echo "Error: " . $error->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateSite'])) {
    try {
        $sql = "UPDATE vaccinationSite SET addrStreet = :addrStreet, addrCity = :addrCity, addrState = :addrState, addrZip = :addrZip WHERE siteName = :siteName";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':siteName', $_POST['siteName'], PDO::PARAM_STR);
        $statement->bindParam(':addrStreet', $_POST['addrStreet'], PDO::PARAM_STR);
        $statement->bindParam(':addrCity', $_POST['addrCity'], PDO::PARAM_STR);
        $statement->bindParam(':addrState', $_POST['addrState'], PDO::PARAM_STR);
        $statement->bindParam(':addrZip', $_POST['addrZip'], PDO::PARAM_STR);

        $statement->execute();

        echo "Vaccination site successfully updated.";
        header("Location: view_vaccination_sites.php"); // Replace with your list page
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
    <title>Edit Vaccination Site</title>
</head>
<body>
    <h2>Edit Vaccination Site</h2>

    <form method="post">
        <input type="hidden" name="siteName" value="<?php echo htmlspecialchars($site['siteName']); ?>">

        <label for="addrStreet">Street Address</label>
        <input type="text" name="addrStreet" id="addrStreet" value="<?php echo htmlspecialchars($site['addrStreet']); ?>" required>

        <label for="addrCity">City</label>
        <input type="text" name="addrCity" id="addrCity" value="<?php echo htmlspecialchars($site['addrCity']); ?>" required>

        <label for="addrState">State</label>
        <input type="text" name="addrState" id="addrState" value="<?php echo htmlspecialchars($site['addrState']); ?>" required>

        <label for="addrZip">Zip Code</label>
        <input type="text" name="addrZip" id="addrZip" value="<?php echo htmlspecialchars($site['addrZip']); ?>" required>

        <input type="submit" name="updateSite" value="Update Site">
    </form>
</body>
</html>
