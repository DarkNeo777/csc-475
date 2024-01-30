



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Simple Database App</title>

    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <h1>Simple Database App</h1>
    <PRE>
<?php
//show debug info


define('DEBUG', FALSE);

// show prepared statement debug information
function show_statement_debug_info($stmt){
    ob_start();
    $stmt->debugDumpParams();
    $r = ob_get_contents();
    ob_end_clean();

    //*
    print_r($r);
    print("\n");
    /*/
    echo explode("\n",$r)[1]."\n";
    //*/
}

try{
 
    require("project/connection.php");

    $allData = json_decode(file_get_contents("dump.json"));

    if(DEBUG){
        // emulate prepared statements so debugDumpParams show full query
        $connection->setAttribute( PDO::ATTR_EMULATE_PREPARES, true );
    }
    

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //delete all departments first
    $connection->query("DELETE FROM patient;");
     
    //read and insert all department data

    $patientCounter = 0;
    $currentIndex = 0; // Track the current index
    
    foreach ($allData->patient as $row) {
        if ($patientCounter >= 25) {
            break; // Exit the loop after inserting 20 rows
        }
        
        if ($currentIndex < 15 || ($currentIndex >= 3500 && $currentIndex < 3510)) {
            $query_str = "INSERT INTO patient VALUES (:patientId,:fName,:mInitial,:lName,:dob,:weight)";
            $stmt = $connection->prepare($query_str);
            
            $stmt->bindParam(":patientId", $row->ID, PDO::PARAM_INT);
            $stmt->bindParam(":fName", $row->F_Name, PDO::PARAM_STR);
            $stmt->bindParam(":mInitial", $row->M_init, PDO::PARAM_STR);
            $stmt->bindParam(":lName", $row->L_Name, PDO::PARAM_STR);
            $stmt->bindParam(":dob", $row->dob, PDO::PARAM_INT);
            $stmt->bindParam(":weight", $row->Weight, PDO::PARAM_INT);

            $stmt->execute();
            
            if(DEBUG){
                show_statement_debug_info($stmt);
            }

            $patientCounter++;
        }
        
        $currentIndex++;
    }
   

    //////////////////////////////////////////////////////
    $connection->query("DELETE FROM vaccinationSite;");

    $vaccination_count = 0;

    foreach ($allData->vaccinationSite as $row) {
        if ($vaccination_count >= 10) {
            break; // Exit the loop after inserting 10 rows
        }

        $query_str = "INSERT INTO vaccinationSite VALUES (:siteName,:addrStreet,:addrCity,:addrState,:addrZip)";
        $stmt = $connection->prepare($query_str);
        
        $stmt->bindParam(":siteName", $row->siteName, PDO::PARAM_STR);
        $stmt->bindParam(":addrStreet", $row->Addr_Street, PDO::PARAM_STR);
        $stmt->bindParam(":addrCity", $row->Addr_City, PDO::PARAM_STR);
        $stmt->bindParam(":addrState", $row->Addr_State, PDO::PARAM_STR);
        $stmt->bindParam(":addrZip", $row->Addr_Zip, PDO::PARAM_INT);

        $stmt->execute();
        
        if(DEBUG){
            show_statement_debug_info($stmt);
        }
        $vaccination_count++;
    }


     //////////////////////////////////////////////////////
    $connection->query("DELETE FROM insured_patient;");
    

    $insured_count = 0;
    //read and insert
    foreach ($allData->insured_patient as $row) {
        if ($insured_count >= 10) {
            break; // Exit the loop after inserting 10 rows
        }


        $query_str = "INSERT INTO insured_patient VALUES (:patientId,:company,:copay)";
        $stmt = $connection->prepare($query_str);
        
        $stmt->bindParam(":patientId", $row->ID, PDO::PARAM_INT);
        $stmt->bindParam(":company", $row->Company, PDO::PARAM_STR);
        $stmt->bindParam(":copay", $row->Co_pay, PDO::PARAM_INT);

        $stmt->execute();
        
        if(DEBUG){
            show_statement_debug_info($stmt);
        }
        $insured_count++;
    }


     //////////////////////////////////////////////////////
    $connection->query("DELETE FROM uninsured_patient;");
  
       
    // Inserting uninsured patients

    $uninsured_count = 0;
    foreach ($allData->uninsured_patient as $row) {
        if ($uninsured_count >= 10) {
            break; // Exit the loop after inserting 10 rows
        }


        $query_str = "INSERT INTO uninsured_patient VALUES (:patientId,:paymentMethod,:addrStreet,:addrCity,:addrState,:addrZip)";
        $stmt = $connection->prepare($query_str);
        
        $stmt->bindParam(":patientId", $row->patientId, PDO::PARAM_INT);
        $stmt->bindParam(":paymentMethod", $row->paymentMethod, PDO::PARAM_STR);
        $stmt->bindParam(":addrStreet", $row->addrStreet, PDO::PARAM_STR);
        $stmt->bindParam(":addrCity", $row->addrCity, PDO::PARAM_STR);
        $stmt->bindParam(":addrState", $row->addrState, PDO::PARAM_STR);
        $stmt->bindParam(":addrZip", $row->addrZip, PDO::PARAM_INT);
    
        $stmt->execute();
        
        if(DEBUG){
            show_statement_debug_info($stmt);
        }
        $uninsured_count++;
    }


    
     
    //////////////////////////////////////////////////////
    $connection->query("DELETE FROM allergy;");



    $allergy_count = 0;
    //read and insert 
    foreach ($allData->allergy as $row) {
        if ($allergy_count >= 10) {
            break; // Exit the loop after inserting 10 rows
        }

        $query_str = "INSERT INTO allergy VALUES (:patientId,:allergyDesc)";
        $stmt = $connection->prepare($query_str);
        

        $allergies_list = implode(" ", $row->Allergy);
        $stmt->bindParam(":patientId", $row->Patient_ID, PDO::PARAM_STR);
        $stmt->bindParam(":allergyDesc", $allergies_list, PDO::PARAM_STR);

        $stmt->execute();
        
        if(DEBUG){
            show_statement_debug_info($stmt);
        }

        $allergy_count++;
    }






  //////////////////////////////////////////////////////
    $connection->query("DELETE FROM vaccine;");
    
    $vaccine_count = 0;
    foreach ($allData->vaccine as $row) {
        if ($vaccine_count >= 10) {
            break; // Exit the loop after inserting 10 rows
        }

        $query_str = "INSERT INTO vaccine VALUES (:scientificName,:disease,:noDoses)";
        $stmt = $connection->prepare($query_str);
        
        $stmt->bindParam(":scientificName", $row->scientificName, PDO::PARAM_STR);
        $stmt->bindParam(":disease", $row->disease, PDO::PARAM_STR);
        $stmt->bindParam(":noDoses", $row->noDose, PDO::PARAM_STR);

        $stmt->execute();
        
        if(DEBUG){
            show_statement_debug_info($stmt);
        }
        $vaccine_count++;
    }



    
     //////////////////////////////////////////////////////
    $connection->query("DELETE FROM lot;");
    
    $lot_count = 0;
    foreach ($allData->lot as $row) {
        if ($lot_count >= 10) {
            break; // Exit the loop after inserting 10 rows
        }


        $query_str = "INSERT INTO lot VALUES (:scientificName,:lotNumber,:manufacturerPlace,:expiration)";
        $stmt = $connection->prepare($query_str);
        
        $stmt->bindParam(":scientificName", $row->scientificName, PDO::PARAM_STR);
        $stmt->bindParam(":lotNumber", $row->lotNumber, PDO::PARAM_INT);
        $stmt->bindParam(":manufacturerPlace", $row->manufacturerPlace, PDO::PARAM_STR);
        $stmt->bindParam(":expiration", $row->expiration, PDO::PARAM_STR);

        $stmt->execute();
        
        if(DEBUG){
            show_statement_debug_info($stmt);
        }
        $lot_count++;
    }

     
/////////////////////////// //////////////////////////////////////////////////////
    $connection->query("DELETE FROM takes;");
    
    $takes_count = 0;
    foreach ($allData->takes as $row) {
        if ($takes_count >= 10) {
            break; // Exit the loop after inserting 10 rows
        }


        $query_str = "INSERT INTO takes VALUES (:patientID,:siteName,:scientificName,:dateTaken)";
        $stmt = $connection->prepare($query_str);
        
        $stmt->bindParam(":patientID", $row->patientId, PDO::PARAM_INT);
        $stmt->bindParam(":siteName", $row->siteName, PDO::PARAM_STR);
        
        $stmt->bindParam(":scientificName", $row->scientificName, PDO::PARAM_STR);
        $stmt->bindParam(":dateTaken", $row->dateTaken, PDO::PARAM_STR);

        $stmt->execute();
        
        if(DEBUG){
            show_statement_debug_info($stmt);
        }
        $takes_count++;
    }


    /////////////////////////// //////////////////////////////////////////////////////
    $connection->query("DELETE FROM billing;");

    $billing_count = 0;
    foreach ($allData->billing as $row) {
        if ($billing_count >= 10) {
            break; // Exit the loop after inserting 10 rows
        }


        // Check if the patientId exists in the uninsured_patient table
        $query_str = "SELECT * FROM uninsured_patient WHERE patientId = :patientId";
        $stmt = $connection->prepare($query_str);
        $stmt->bindParam(":patientId", $row->patientId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // If the patientId exists, insert the record into the billing table
        if ($result) {
            $query_str = "INSERT INTO billing VALUES (:patientId,:siteName)";
            $stmt = $connection->prepare($query_str);
            
            $stmt->bindParam(":patientId", $row->patientId, PDO::PARAM_INT);
            $stmt->bindParam(":siteName", $row->siteName, PDO::PARAM_STR);
            
            $stmt->execute();
            
            if(DEBUG){
                show_statement_debug_info($stmt);
            }
        }
        $billing_count++;
    }

    print("Deleting all previous data and inserted new data");




    
} catch(PDOException $error) {
    //if database failed, print error and exit;
    echo "Database error: " . $error->getMessage() . "<BR>";
    die;
}

?>
</PRE>

    <ul>
    
      <li>
        <a href="view_patients.php"><strong>View Patients</strong></a>
      </li>
      <li>
        <a href="view_vaccine.php"><strong>View Vaccines</strong></a>
      </li>
      <li>
        <a href="view_vaccination_sites.php"><strong>View Vaccination Sites</strong></a>
      </li>
      <li>
        <a href="view_takes.php"><strong>View Takes</strong></a>
      </li>
    </ul>
  </body>
</html>