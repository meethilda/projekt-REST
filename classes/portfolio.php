<?php
// Class for Work
// Uses the table Work in database cv_api
class Portfolio
{
    // Tablename
    private $table_name = 'Portfolio';
    // Connection variable
    private $conn;

    // Variables
    public $Pid;
    public $Ptitle;
    public $Purl;
    public $Pdesc;
    public $Pcreated;
    public $UserID;

    // Create database connection
    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
    }

    // GET all info
    public function GET($id)
    {
        // If empty id
        if (!$id) {
            // Prepare query
            $query = "SELECT * FROM " . $this->table_name . " order by Pcreated ASC";
        } else {
            // Prepare query with ID
            $query = "SELECT * FROM " . $this->table_name . " WHERE Pid=" . $id;
        }

        // Send request
        $result = mysqli_query($this->conn, $query);
        // Call function JSON for output
        $this->JSON($result);
    }

    public function POST($input)
    {
        // Foreach-loop for inputs
        foreach ($input as $key => $val) {
            // Check if value is empty
            if (empty($val)) {
                // Replace with null-string
                $this->$key = 'NULL';
                // If value is a string
            } else if (is_string($val)) {
                // Add inside ''
                $this->$key = "'" . $val . "'";
            } else {
                // Set values to variables
                $this->$key = $val;
            }
        }

        // Prepare query
        $query = "INSERT INTO " .
        $this->table_name . "(`Ptitle`, `Purl`, `Pdesc`, `Pcreated`, `UserID`)
        VALUES (
            " . $this->Ptitle . ",
            " . $this->Purl . ",
            " . $this->Pdesc . ",
            " . $this->Pcreated . ",
            " . $this->UserID . ")";

        // Send request
        $result = mysqli_query($this->conn, $query);
        // If result is true, call function get
        if ($result) {
            $id = null;
            $this->GET($id);
        } else {
            return $_SERVER['msg-error'] = 'Something went wrong when posting to ' . $this->table_name;
        }
    }

    // Put/update with id
    public function PUT($input, $id)
    {
        // Foreach-loop for inputs
        foreach ($input as $key => $val) {
            // Check if value is empty
            if (empty($val)) {
                // Replace with null-string
                $this->$key = 'NULL';
                // If value is a string
            } else if (is_string($val)) {
                // Add inside ''
                $this->$key = "'" . $val . "'";
            } else {
                // Set values to variables
                $this->$key = $val;
            }
        }

        // Prepare query
        $query = "UPDATE " .
        $this->table_name .
        " SET
            `Ptitle`=" . $this->Ptitle . ",
            `Purl`=" . $this->Purl . ",
            `Pdesc`=" . $this->Pdesc . ",
            `Pcreated`=" . $this->Pcreated . ",
            `UserID`=" . $this->UserID . "
        WHERE
            Pid=" . $id;

        // Send request
        $result = mysqli_query($this->conn, $query);
        // If result is true, call function get
        if ($result) {
            $id = null;
            $this->GET($id);
        } else {
            return $_SERVER['msg-error'] = 'Something went wrong when updating to ' . $this->table_name;
        }
    }

    // Delete with id
    public function DELETE($id)
    {
        $query = "DELETE FROM " . 
            $this->table_name . 
        " WHERE Pid=" . $id;
        $result = mysqli_query($this->conn, $query);
        // If result is true, call function get
        if ($result) {
            $id = null;
            $this->GET($id);
        } else {
            return $_SERVER['msg-error'] = 'Something went wrong when deleting in ' . $this->table_name;
        }
    }

    // JSON encode and output
    private function JSON($result)
    {
        // Check numb of rows
        if ($result) {
            $num = $result->num_rows;
        } else {
            $num = '';
        }
        // Create array
        $outputArr = [];
        // Output object
        if ($num > 0) {
            while ($row = $result->fetch_assoc()) {
                // Extract each value
                extract($row);
                // Create array items
                $arrItem = [
                    'Pid' => $Pid,
                    'Ptitle' => $Ptitle,
                    'Purl' => $Purl,
                    'Pdesc' => $Pdesc,
                    'Pcreated' => $Pcreated,
                    'UserID' => $UserID
                ];
                // Push to array
                array_push($outputArr, $arrItem);
            }
            // OK response
            http_response_code(200);

            // Output JSON
            echo json_encode($outputArr);
        } else {
            // Add error response code
            http_response_code(404);

            // If no items found
            echo json_encode(
                ['message' => 'No portfolio found']
            );
        }
    }
}
