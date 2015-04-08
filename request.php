<?php

/**
 * @since 1.0
 * @link $URL$
 * @author $Author$
 * @version $Revision$
 * @Last Modified$
 */

/**
 *
 * @author amara_thota
 * @name People class
 * @desc Reads csv and Creates Person Object for each record
 * @return sorted People data
 */
class People
{

    public $currentCSVRow;

    public $csvRowContent;

    public $csvData;

    public $peopleData;

    public $returnData;

    public $sortByColumn; // Sory by Last name / age etc...

    public $sortByOrder; // Sory by ASC /DSC

    public $status; // OK / ERR

    public $message; // Data found or not?

    public $csvFileName;

    public $configs;

    public $logFileName;

    /**
     * @desc Read config setting from project.ini
     */
    function __construct()
    {
        $this->configs = parse_ini_file("project.ini", false);
        $this->csvFileName = $this->configs['csvFileName'];
        $this->logFileName = $this->configs['logFile'];
        $this->sortByOrder = $this->configs['sortBy'];
    }

    /**
     * 
     * @name readCSV
     * @desc Reads csv
     * @param csv data
     * @return array
     */
    public function readCSV()
    {
        $file = fopen($this->csvFileName, "r"); // open csv
        
        if ($file and is_readable('people.csv')) {
            $this->debugLog("CSV is readble so proceeding... ");
            while (! feof($file)) {
                $this->currentCSVRow = fgets($file);
                $this->csvRowContent = explode(",", trim($this->currentCSVRow));
                $this->csvData[] = $this->csvRowContent;
            }
        } else {
            $this->debugLog("could not open the csv file or csv file is not readbale ");
            $this->csvData = array(); // return empty array
        }
        
        fclose($file); // close file
        
        return $this->csvData;
    }

    /**
     * @name sortOrder
     * @desc calls from usort()
     * @return sorted Array based on Order Column
     */
    public function sortOrder($a, $b)
    {
        $val = $this->sortByColumn;
        
        $sortByOrder = $this->sortByOrder;
        
        if ($a[$val] == $b[$val]) {
            return 0;
        }
        
        if ($sortByOrder == 'DSC') {
            return $a[$val] < $b[$val] ? 1 : - 1;
        } else {
            return $a[$val] > $b[$val] ? 1 : - 1;
        }
    }

    /**
     * @name getPeoplData
     * @desc send back People data in array format including status, Message
     * @return array 
     */
    public function getPeoplData()
    {
        if (count($this->peopleData) > 0) {
            
            $this->debugLog("csv data found");
            $this->status = 'OK';
            $this->message = 'Data found';
            
            // Sort array by name by default
            usort($this->peopleData, array(
                'People',
                'sortOrder'
            ));
        } else {
            
            $this->debugLog("csv does not have any data");
            $status = 'ERR';
            $message = 'Data not found';
            $this->peopleData = array(); // send empty array , means no data found
        }
        
        $this->returnData = array(
            "status" => $this->status,
            "message" => $this->message,
            "people" => $this->peopleData
        );
        
        echo json_encode($this->returnData);
    }

    /**
     * @name debugLog
     * @desc Log all server side meesages
     * 
     */
    function debugLog($msg)
    {
        $log = fopen($this->logFileName, "a+");
        
        if ($log) {
            
            $dt = date("Y-m-d H:i:s");
            
            if (is_string($msg)) {
                fprintf($log, "%s [%s]: %s \n", $dt, $_SERVER['SERVER_ADDR'], $msg);
            } else {
                ob_start();
                print_r($msg);
                fputs($log, ob_get_clean());
            }
            
            fclose($log);
        }
    }

    function __destruct()
    {}
}

/**
 * @name Person Class
 * @desc Prepares Person data
 */
class Person
{

    public $name;

    public $height;

    public $dob;

    public $gender;

    public $personData;

    public $record;

    public function __construct()
    {}

    /**
     * @name getPersonData
     * @desc prepares Individual Person data
     * @return array
     */
    public function getPersonData()
    {
        $this->name = trim($this->personData['0']);
        $this->lastName = $this->extractLastName();
        $this->height = $this->personData['1'];
        $this->gender = $this->personData['2'];
        
        $this->dob = strtotime($this->personData['3']); // convert to unixtime stamp
        $this->dobDisplay = date('d-m-Y', $this->dob); // Disaply d-m-Y format date
        
        $this->record = array(
            'name' => $this->name,
            'lastName' => $this->lastName,
            'height' => $this->height,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'dobDisplay' => $this->dobDisplay
        )
        ;
        
        return $this->record;
    }
    
    /**
     * @name extractLastName
     * @desc extract last name if it exists
     * @return lastName
     */
    public function extractLastName()
    {
        $nameSplit = explode(" ", $this->name);
        
        if (count($nameSplit) > 1) {
            $lastName = array_pop($nameSplit);
        } else {
            $lastName = '';
        }
        
        return $lastName;
    }
}

$people = new People(); 

// Tabe sory by column, by default by set by name
if (isset($_GET['sorByColumn'])) {
    $people->sortByColumn = $_GET['sorByColumn'];
} else {
    $people->sortByColumn = 'name'; 
}

//Sort order ASC/DSC , by default set by ASC
if (isset($_GET['sortByOrder'])) {
    $people->sortByOrder = $_GET['sortByOrder'];
} else {
    $people->sortByOrder = 'ASC'; 
}


$csvData = $people->readCSV(); // Read csv

$person = new Person(); 


//Assuming "Create a person for each record" is Mandatory
foreach ($csvData as $record) {
    $person->personData = $record;
    $singleUser = $person->getPersonData();
    $people->peopleData[] = $singleUser;
}

$people->getPeoplData();

// Parse CSV
// Create a person for each record
// associate it to the higher class People
// return data back to index.php

?>