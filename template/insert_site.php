<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitSite'])) {
    require_once("project/connection.php");

    try {
        $connection = new PDO($dsn, $username, $password, $options);
        
        $new_site = array(
            "siteName"   => $_POST['siteName'],
            "addrStreet" => $_POST['addrStreet'],
            "addrCity"   => $_POST['addrCity'],
            "addrState"  => $_POST['addrState'],
            "addrZip"    => $_POST['addrZip']
        );

        $sql = sprintf(
                "INSERT INTO %s (%s) values (%s)",
                "vaccinationSite",
                implode(", ", array_keys($new_site)),
                ":" . implode(", :", array_keys($new_site))
        );
        
        $statement = $connection->prepare($sql);
        $statement->execute($new_site);

        echo "<blockquote>{$new_site['siteName']} successfully added.</blockquote>";
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
<h2>Add a Vaccination Site</h2>
<ul>

<li>
    <a href="index.php"><strong>Index</strong></a> Homepage
</li>

<li>
    <a href="view_vaccination_sites.php"><strong>View Sites</strong></a> 
</li>
</ul>

<form method="post">
    <label for="siteName">Site Name</label>
    <input type="text" name="siteName" id="siteName" required>

    <label for="addrStreet">Street Address</label>
    <input type="text" name="addrStreet" id="addrStreet" required>

    <label for="addrCity">City</label>
    <input type="text" name="addrCity" id="addrCity" required>

    <label for="addrState">State</label>
    <input type="text" name="addrState" id="addrState" required>

    <label for="addrZip">Zip Code</label>
    <input type="text" name="addrZip" id="addrZip" required>

    <input type="submit" name="submitSite" value="Submit">
</form>

</body>
</html>