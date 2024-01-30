<?php
try {
    require_once("project/connection.php");

    $sql = "SELECT takes.patientId, takes.siteName, takes.scientificName, takes.dateTaken, 
                   patient.fName AS patientFirstName, patient.lName AS patientLastName, 
                   vaccinationSite.addrCity, vaccinationSite.addrState, 
                   vaccine.disease 
            FROM takes 
            JOIN patient ON takes.patientId = patient.patientId 
            JOIN vaccinationSite ON takes.siteName = vaccinationSite.siteName 
            JOIN vaccine ON takes.scientificName = vaccine.scientificName";

    $statement = $connection->prepare($sql);
    $statement->execute();

    $takesRecords = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>

<?php
try {
    require_once("project/connection.php");

    if (isset($_POST['search'])) {
        // Search logic for 'takes'
        $sql = "SELECT * FROM takes WHERE patientId = :patientId"; // Adjust as needed
        $patientId = $_POST['patientId']; // Adjust the search criterion as needed

        $statement = $connection->prepare($sql);
        $statement->bindParam(':patientId', $patientId, PDO::PARAM_STR);
    } else {
        // Default: display all records from 'takes'
        $sql = "SELECT * FROM takes";
        $statement = $connection->prepare($sql);
    }

    $statement->execute();
    $result = $statement->fetchAll();
} catch(PDOException $error) {
    echo "Error: " . $error->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    try {
        $compositeKey = $_POST['compositeKey']; // Adjust this based on your form

        // Extract keys from the composite key
        list($patientId, $siteName, $scientificName, $dateTaken) = explode('|', $compositeKey);

        // Prepare SQL statement for deletion
        $sql = "DELETE FROM takes WHERE patientId = :patientId AND siteName = :siteName AND scientificName = :scientificName AND dateTaken = :dateTaken";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':patientId', $patientId, PDO::PARAM_STR);
        $statement->bindParam(':siteName', $siteName, PDO::PARAM_STR);
        $statement->bindParam(':scientificName', $scientificName, PDO::PARAM_STR);
        $statement->bindParam(':dateTaken', $dateTaken);

        $statement->execute();
        echo "Record successfully deleted.";

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
    <title>Takes Records</title>
</head>
<body>
<ul>

<li>
    <a href="index.php"><strong>Index</strong></a> Homepage
</li>


</ul>
    <h2>Takes Records</h2>

    
    <h4>
            <a href="insert_takes.php"><strong>Insert Takes</strong></a> 
</h4>
    <form method="post">
    <label for="patientId">Search by Patient ID:</label>
    <input type="text" id="patientId" name="patientId">
    <input type="submit" name="search" value="Search">
</form>

    <?php if ($takesRecords && $statement->rowCount() > 0) { ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Site Name</th>
                    <th>Site City</th>
                    <th>Site State</th>
                    <th>Vaccine</th>
                    <th>Disease</th>
                    <th>Date Taken</th>
                    <th>Delete </th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($takesRecords as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["patientId"]); ?></td>
                        <td><?php echo htmlspecialchars($row["patientFirstName"]) . " " . htmlspecialchars($row["patientLastName"]); ?></td>
                        <td><?php echo htmlspecialchars($row["siteName"]); ?></td>
                        <td><?php echo htmlspecialchars($row["addrCity"]); ?></td>
                        <td><?php echo htmlspecialchars($row["addrState"]); ?></td>
                        <td><?php echo htmlspecialchars($row["scientificName"]); ?></td>
                        <td><?php echo htmlspecialchars($row["disease"]); ?></td>
                        <td><?php echo htmlspecialchars($row["dateTaken"]); ?></td>
                        <td>
                    <form method="post">
                        <input type="hidden" name="compositeKey" value="<?php echo htmlspecialchars($row['patientId'] . '|' . $row['siteName'] . '|' . $row['scientificName'] . '|' . $row['dateTaken']); ?>">
                        <input type="submit" name="delete" value="Delete">
                    </form>
                </td>
                        <td>
                             <a href='edit_takes.php?patientId=<?php echo htmlspecialchars($row["patientId"]); ?>'>Edit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No records found.</p>
    <?php } ?>
</body>
</html>
