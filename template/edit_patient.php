<?php
if (isset($_GET['patientId'])) {
    try {
        require_once("project/connection.php");

        // Fetch existing patient data
        $sql = "SELECT * FROM patient WHERE patientId = :patientId";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':patientId', $_GET['patientId']);
        $statement->execute();

        $patient = $statement->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $error) {
        echo "Error: " . $error->getMessage();
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    try {
        // Update logic
        $sql = "UPDATE patient SET fName = :fName, mInitial = :mInitial, lName = :lName, dob = :dob, weight = :weight WHERE patientId = :patientId";

        $statement = $connection->prepare($sql);

        // Bind parameters
       // Bind parameters
$statement->bindParam(':patientId', $_POST['patientId'], PDO::PARAM_STR);
$statement->bindParam(':fName', $_POST['fName'], PDO::PARAM_STR);
$statement->bindParam(':mInitial', $_POST['mInitial'], PDO::PARAM_STR);
$statement->bindParam(':lName', $_POST['lName'], PDO::PARAM_STR);
        $statement->bindParam(':dob', $_POST['dob']);
    $statement->bindParam(':weight', $_POST['weight']);



        $statement->execute();

        echo "Patient successfully updated.";
        header("Location: view_patients.php"); // Redirect to patient list page
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
    <title>Update Patient</title>
</head>
<body>

<li>
    <a href="index.php"><strong>Index</strong></a> Homepage
</li>

<li>
    <a href="view_patients.php"><strong>View Patient</strong></a> 
</li>
</ul>

    <h2>Update Patient</h2>



    <form method="post">
        <input type="hidden" name="patientId" value="<?php echo htmlspecialchars($patient['patientId']); ?>">

        <input type="text" name="fName" value="<?php echo htmlspecialchars($patient['fName']); ?>" required>
        <input type="text" name="mInitial" value="<?php echo htmlspecialchars($patient['mInitial']); ?>" required>
        <input type="text" name="lName" value="<?php echo htmlspecialchars($patient['lName']); ?>" required>
        <input type="text" name="dob" value="<?php echo htmlspecialchars($patient['dob']); ?>" required>
        <input type="text" name="weight" value="<?php echo htmlspecialchars($patient['weight']); ?>" required>
        <!-- Add other fields for mInitial, lName, dob, weight -->

        <input type="submit" name="save" value="Update Patient">
    </form>
</body>
</html>
