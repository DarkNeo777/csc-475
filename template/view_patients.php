<?php
try  {
    require_once("project/connection.php");



    if (isset($_POST['search'])) {
        // If the search form is submitted
        $sql = "SELECT * FROM patient WHERE fName = :fName";
        $fName = $_POST['fName'];

        $statement = $connection->prepare($sql);
        $statement->bindParam(':fName', $fName, PDO::PARAM_STR);
    } else {
        // Default behavior: display all patients
        $sql = "SELECT * FROM patient";
        $statement = $connection->prepare($sql);
    }

    $statement->execute();
    $result = $statement->fetchAll();
} catch(PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    try {
        require_once("project/connection.php");

        $patientId = $_POST['patientId'];

        // Prepare SQL statement for deletion
        $sql = "DELETE FROM patient WHERE patientId = :patientId";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':patientId', $patientId, PDO::PARAM_STR);

        $statement->execute();
        echo "Patient successfully deleted.";

        // Optionally, redirect back to the same page to see the updated list
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
    <title>Patients List</title>
</head>
<body>



    <ul>

        <li>
            <a href="index.php"><strong>Index</strong></a> Homepage
        </li>
        
        
    </ul>
    <h2>Search for a Patient</h2>

    <form method="post">
        <label for="fName">First Name:</label>
        <input type="text" id="fName" name="fName">
        <input type="submit" name="search" value="Search">
    </form>

    <h2>Patients List</h2>

    <h4>
            <a href="insert_patient.php"><strong>Insert Patient</strong></a> 
</h4>
    <?php
    if ($result && $statement->rowCount() > 0) { ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>First Name</th>
                    <th>Middle Initial</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Weight</th>
                    <th>Delete</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["patientId"]); ?></td>
                        <td><?php echo htmlspecialchars($row["fName"]); ?></td>
                        <td><?php echo htmlspecialchars($row["mInitial"]); ?></td>
                        <td><?php echo htmlspecialchars($row["lName"]); ?></td>
                        <td><?php echo htmlspecialchars($row["dob"]); ?></td>
                        <td><?php echo htmlspecialchars($row["weight"]); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="patientId" value="<?php echo htmlspecialchars($row["patientId"]); ?>">
                                <input type="submit" name="delete" value="Delete">
                            </form>
                        </td>
                        <td>
                             <a href='edit_patient.php?patientId=<?php echo htmlspecialchars($row["patientId"]); ?>'>Edit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No patients found.</p>
    <?php }
    ?>
</body>
</html>

<!-------------------------------------------------------------------------------------------------------------------------------------->


