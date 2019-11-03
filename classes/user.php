<?php
// Class for Users
// Uses the table User in database cv_api
class User
{
    // Tablename
    private $table_name = 'User';
    // Connection variable
    private $conn;

    // Variables
    public $Uid;
    public $Uemail;
    public $Udesc;
    public $UfirstName;
    public $UlastName;
    public $Upassword;
    public $facebook;
    public $instagram;
    public $linkedin;

    // Create database connection
    public function __construct($db_conn)
    {
        $this->conn = $db_conn;
    }

    // Get login user
    public function GetUser($input)
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
        $query = "SELECT Uid FROM " . $this->table_name . " WHERE Uemail=" . $this->Uemail . " AND Upassword=" . $this->Upassword;
        // Send request
        $result = mysqli_query($this->conn, $query);

        // Check numb of rows
        if ($result) {
            $num = $result->num_rows;

            // If result is found
            if ($num > 0) {
                // Get id
                while ($row = $result->fetch_assoc()) {
                    $this->GET($row['Uid']);
                }
            } else {
                return $_SERVER['msg-error'] = 'No user found. Maybe email/password is wrong?';
            }
        } else {
            return $_SERVER['msg-error'] = 'Something went wrong when login';
        }
    }

    // GET all info
    public function GET($id)
    {
        // If empty id
        if (!$id) {
            // Prepare query
            $query = "SELECT * FROM " . $this->table_name;
        } else {
            // Prepare query with ID
            $query = "SELECT * FROM " . $this->table_name . " WHERE Uid=" . $id;
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
        $this->table_name . "(`Uemail`, `Udesc`, `UfirstName`, `UlastName`, `Upassword`, `facebook`, `instagram`, `linkedin`)
        VALUES (
            " . $this->Uemail . ",
            " . $this->Udesc . ",
            " . $this->UfirstName . ",
            " . $this->UlastName . ",
            " . $this->Upassword . ",
            " . $this->facebook . ",
            " . $this->instagram . ",
            " . $this->linkedin . ")";

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
            `Uemail`=" . $this->Uemail . ",
            `Udesc`=" . $this->Udesc . ",
            `UfirstName`=" . $this->UfirstName . ",
            `UlastName`=" . $this->UlastName . ",
            `facebook`=" . $this->facebook . ",
            `instagram`=" . $this->instagram . ",
            `linkedin`=" . $this->linkedin . "
        WHERE
            Uid=" . $id;

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
        $query = "DELETE FROM `User` WHERE Uid=" . $id;
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
                    'Uid' => $Uid,
                    'Uemail' => $Uemail,
                    'Udesc' => $Udesc,
                    'UfirstName' => $UfirstName,
                    'UlastName' => $UlastName,
                    'Upassword' => 'hidden',
                    'facebook' => $facebook,
                    'instagram' => $instagram,
                    'linkedin' => $linkedin,
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
                ['message' => 'No users found']
            );
        }
    }
}
