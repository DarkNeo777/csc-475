
<?php
require_once("project/connection.php");

// Handle the POST request to update the record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateTake'])) {
    try {

        
        $originalDateTaken = $_POST['originalDateTaken'];

        // Debugging line to check the value of originalDateTaken
        var_dump($originalDateTaken); // Remove this line after debugging
        
        $statement = $connection->prepare($sql);
        // Bind parameters...
        $statement->execute();

        // Extract the original primary key values from hidden inputs
        $originalPatientId = $_POST['originalPatientId'];
        $originalSiteName = $_POST['originalSiteName'];
        $originalScientificName = $_POST['originalScientificName'];
        $originalDateTaken = $_POST['originalDateTaken'];

        // Extract the updated values from the form
        $updatedPatientId = $_POST['patientId'];
        $updatedSiteName = $_POST['siteName'];
        $updatedScientificName = $_POST['scientificName'];

        // Update SQL statement (excluding dateTaken)
        $sql = "UPDATE takes 
                SET patientId = :updatedPatientId, 
                    siteName = :updatedSiteName, 
                    scientificName = :updatedScientificName
                WHERE patientId = :originalPatientId AND 
                      siteName = :originalSiteName AND 
                      scientificName = :originalScientificName AND 
                      dateTaken = :originalDateTaken";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':updatedPatientId', $updatedPatientId);
        $statement->bindParam(':updatedSiteName', $updatedSiteName);
        $statement->bindParam(':updatedScientificName', $updatedScientificName);
        $statement->bindParam(':originalPatientId', $originalPatientId);
        $statement->bindParam(':originalSiteName', $originalSiteName);
        $statement->bindParam(':originalScientificName', $originalScientificName);
        $statement->bindParam(':originalDateTaken', $originalDateTaken);

        $statement->execute();

        echo "Record successfully updated.";
        header("Location: view_takes.php"); // Redirect to the takes list page
        exit;
    } catch (PDOException $error) {
        echo "Error: " . $error->getMessage();
    }
}

// Code for fetching dropdown options remains unchanged
// ...

?>

<?php
require_once("project/connection.php");

// Fetch options for dropdowns
try {
    // Fetch Patient IDs

    $patientSql = "SELECT patientId, CONCAT(fName, ' ', lName) AS patientName FROM patient";
    $patientStmt = $connection->prepare($patientSql);
    $patientStmt->execute();
    $patients = $patientStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch Site Names and Cities
    $siteSql = "SELECT siteName, addrCity FROM vaccinationSite";
    $siteStmt = $connection->prepare($siteSql);
    $siteStmt->execute();
    $sites = $siteStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch Scientific Names and Diseases
    $vaccineSql = "SELECT scientificName, disease FROM vaccine";
    $vaccineStmt = $connection->prepare($vaccineSql);
    $vaccineStmt->execute();
    $vaccines = $vaccineStmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
    echo "Error: " . $error->getMessage();
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Take Record</title>
</head>
<body>
    <h2>Edit Take Record</h2>
    <li>
    <a href="index.php"><strong>Index</strong></a> Homepage
</li>

<li>
    <a href="view_takes.php"><strong>View Takes</strong></a> 
</li>
</ul>

    <form method="post">
        <input type="hidden" name="originalPatientId" value="<?php echo htmlspecialchars($take['patientId']); ?>">
        <input type="hidden" name="originalSiteName" value="<?php echo htmlspecialchars($take['siteName']); ?>">
        <input type="hidden" name="originalScientificName" value="<?php echo htmlspecialchars($take['scientificName']); ?>">
        <input type="hidden" name="originalDateTaken" value="<?php echo htmlspecialchars($take['dateTaken']); ?>">

        <label for="patientId">Patient:</label>
        <select name="patientId" id="patientId">
            <?php foreach ($patients as $patient) { ?>
                <option value="<?php echo htmlspecialchars($patient['patientId']); ?>" <?php echo $patient['patientId'] == $take['patientId'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($patient['patientName']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="siteName">Site Name:</label>
        <select name="siteName" id="siteName">
            <?php foreach ($sites as $site) { ?>
                <option value="<?php echo htmlspecialchars($site['siteName']); ?>" <?php echo $site['siteName'] == $take['siteName'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($site['siteName']) . " - " . htmlspecialchars($site['addrCity']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="scientificName">Vaccine:</label>
        <select name="scientificName" id="scientificName">
            <?php foreach ($vaccines as $vaccine) { ?>
                <option value="<?php echo htmlspecialchars($vaccine['scientificName']); ?>" <?php echo $vaccine['scientificName'] == $take['scientificName'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($vaccine['scientificName']) . " - " . htmlspecialchars($vaccine['disease']); ?>
                </option>
            <?php } ?>
        </select>

        <input type="submit" name="updateTake" value="Update Record">
    </form>
</body>
</html>
