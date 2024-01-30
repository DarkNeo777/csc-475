<?php
if (isset($_POST['submitVaccine'])) {
    require_once("project/connection.php");

    try {
        $connection = new PDO($dsn, $username, $password, $options);
        
        $new_vaccine = array(
            "scientificName" => $_POST['scientificName'],
            "disease"        => $_POST['disease'],
            "noDoses"        => $_POST['noDoses']
        );

        $sql = sprintf(
                "INSERT INTO %s (%s) values (%s)",
                "vaccine",
                implode(", ", array_keys($new_vaccine)),
                ":" . implode(", :", array_keys($new_vaccine))
        );

        $statement = $connection->prepare($sql);
        $statement->execute($new_vaccine);

        echo "<blockquote>{$new_vaccine['scientificName']} successfully added.</blockquote>";
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
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
<h2>Add a Vaccine</h2>
<ul>

<li>
    <a href="index.php"><strong>Index</strong></a> Homepage
</li>

<li>
    <a href="view_vaccine.php"><strong>View Vaccines</strong></a> 
</li>
</ul>

<form method="post">
    <label for="scientificName">Scientific Name</label>
    <input type="text" name="scientificName" id="scientificName" required>

    <label for="disease">Disease</label>
    <input type="text" name="disease" id="disease" required>

    <label for="noDoses">Number of Doses</label>
    <input type="number" name="noDoses" id="noDoses" required>

    <input type="submit" name="submitVaccine" value="Submit">
</form>

    
</body>
</html>