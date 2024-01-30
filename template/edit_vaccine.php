<?php
if (isset($_GET['scientificName'])) {
    try {
        require_once("project/connection.php");

        // Fetch existing vaccine data
        $sql = "SELECT * FROM vaccine WHERE scientificName = :scientificName";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':scientificName', $_GET['scientificName']);
        $statement->execute();

        $vaccine = $statement->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $error) {
        echo "Error: " . $error->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    try {
        // Update logic
        $sql = "UPDATE vaccine SET disease = :disease, noDoses = :noDoses WHERE scientificName = :scientificName";

        $statement = $connection->prepare($sql);
        
        // Bind parameters
        $statement->bindParam(':scientificName', $_POST['scientificName'], PDO::PARAM_STR);
        $statement->bindParam(':disease', $_POST['disease'], PDO::PARAM_STR);
        $statement->bindParam(':noDoses', $_POST['noDoses'], PDO::PARAM_INT);

        $statement->execute();

        echo "Vaccine successfully updated.";
        header("Location: view_vaccine.php"); // Redirect to vaccine list page
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


<li>
    <a href="index.php"><strong>Index</strong></a> Homepage
</li>

<li>
    <a href="view_vaccine.php"><strong>View Vaccines</strong></a> 
</li>
</ul>
<h2>Update Vaccine</h2>
<form method="post">
<input type="hidden" name="scientificName" value="<?php echo htmlspecialchars($vaccine['scientificName']); ?>">

    <label for="disease">Disease</label>
    <input type="text" name="disease" id="disease" value="<?php echo htmlspecialchars($vaccine['disease']); ?>" required>

    <label for="noDoses">Number of Doses</label>
    <input type="number" name="noDoses" id="noDoses" value="<?php echo htmlspecialchars($vaccine['noDoses']); ?>" required>

    <input type="submit" name="update" value="Update Vaccine">
</form>

</body>
</html>