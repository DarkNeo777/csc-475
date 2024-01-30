

<?php


if (isset($_POST['submit'])) {
    require_once("project/connection.php");
    
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        
        $new_patient = array(
            "patientId" => $_POST['patientId'],
            "fName"     => $_POST['fName'],
            "mInitial"  => $_POST['mInitial'],
            "lName"     => $_POST['lName'],
            "dob"       => $_POST['dob'],
            "weight"    => $_POST['weight']
        );

        $sql = sprintf(
                "INSERT INTO %s (%s) values (%s)",
                "patient",
                implode(", ", array_keys($new_patient)),
                ":" . implode(", :", array_keys($new_patient))
        );
        
        $statement = $connection->prepare($sql);
        $statement->execute($new_patient);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>


<?php if (isset($_POST['submit']) && $statement) { ?>
    <blockquote><?php echo $_POST['fName']; ?> successfully added.</blockquote>
<?php } ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<ul>

<li>
    <a href="index.php"><strong>Index</strong></a> Homepage
</li>

<li>
    <a href="view_patients.php"><strong>View Patient</strong></a> 
</li>
</ul>
<h2>Add a Patient</h2>

<form method="post">
    <label for="patientId">Patient ID</label>
    <input type="text" name="patientId" id="patientId" required>

    <label for="fName">First Name</label>
    <input type="text" name="fName" id="fName" required>

    <label for="mInitial">Middle Initial</label>
    <input type="text" name="mInitial" id="mInitial">

    <label for="lName">Last Name</label>
    <input type="text" name="lName" id="lName" required>

    <label for="dob">Date of Birth</label>
    <input type="date" name="dob" id="dob" required>

    <label for="weight">Weight</label>
    <input type="number" name="weight" id="weight" min="0" step="0.01" required>

    <input type="submit" name="submit" value="Submit">
</form>

    
</body>
</html>