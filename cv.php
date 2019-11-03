<?php
// Webbutveckling III, HT19
// Mathilda Edström, Webbutveckling HT18
//
// REST-webbtjänst som behandlar User, Studies, Work, Portfolio
//

// Requests
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

if ($request[0] != 'api') {
    http_response_code(404);
    exit;
}

// Allow Origin, multiple
/*
$http_origin = $_SERVER['HTTP_ORIGIN'];
if ($http_origin == 'http://studenter.miun.se/~maed1801/dt173g/projekt-ADMIN/admin/' || $http_origin == 'http://localhost:3000/') {
    header("Access-Control-Allow-Origin: $http_origin");
}
*/

// Required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

// Includes
include_once 'config/database.php';
foreach (glob('classes/*.php') as $filename) {
    include $filename;
}

// Instance of class
$user = new User($db_conn);
$studie = new Studies($db_conn);
$work = new Work($db_conn);
$portfolio = new Portfolio($db_conn);

// Variable of id
$id = null;

// Check if value in GET id
if (isset($_GET['id'])) {
    if (intval($_GET['id'])) {
        // Declare variable with value
        $id = $_GET['id'];
    } else {
        $id = 'null';
    }
}

// HTTP method of GET, POST, PUT and DELETE
switch ($method) {
    case 'GET':
        // If string two in url is not empty
        if (!empty($request[1])) {
            // Take string
            switch ($request[1]) {
                // If string = users
                case 'users':
                    // Send id as parameter
                    $user->GET($id);
                    break;
                // If string = studies
                case 'studies':
                    // Send id as parameter
                    $studie->GET($id);
                    break;
                // If string = work
                case 'work':
                    // Send id as parameter
                    $work->GET($id);
                    break;
                // If string = work
                case 'portfolio':
                    // Send id as parameter
                    $portfolio->GET($id);
                    break;
                default:
                    // Add error response code
                    http_response_code(404);

                    // If no items found
                    echo json_encode(
                        ['message' => 'Nothing to see here']
                    );
                    break;
            }
        } else {
            // Add error response code
            http_response_code(404);

            // Echo JSON
            echo json_encode(
                ['message' => 'Use search words']
            );
        }
        break;
    case 'POST':
        if ($_COOKIE['login'] || $request[2] == 'login') {
            if (!empty($request[1])) {
                switch ($request[1]) {
                    case 'users':
                        if (!empty($request[2]) && $request[2] == 'login') {
                            $user->GetUser($input);
                        } else {
                            $user->POST($input);
                        }
                        break;
                    case 'studies':
                        $studie->POST($input);
                        break;
                    case 'work':
                        $work->POST($input);
                        break;
                    case 'portfolio':
                        $portfolio->POST($input);
                        break;
                    default:
                        // Add error response code
                        http_response_code(404);

                        // If no items found
                        echo json_encode(
                            ['message' => 'Nothing to see here']
                        );
                        break;
                }
            } else {
                // Add error response code
                http_response_code(404);

                // Echo JSON
                echo json_encode(
                    ['message' => 'Nothing to post here']
                );
            }
        }
        break;
    case 'PUT':
        if ($_COOKIE['login']) {
            // If string two in url is not empty
            if (!empty($request[1])) {
                if (intval($id)) {
                    // Take string
                    switch ($request[1]) {
                        // If string = users
                        case 'users':
                            // Send id as parameter
                            $user->PUT($input, $id);
                            break;
                        // If string = studies
                        case 'studies':
                            // Send id as parameter
                            $studie->PUT($input, $id);
                            break;
                        // If string = work
                        case 'work':
                            // Send id as parameter
                            $work->PUT($input, $id);
                            break;
                        // If string = portfolio
                        case 'portfolio':
                            // Send id as parameter
                            $portfolio->PUT($input, $id);
                            break;
                        default:
                            // Add error response code
                            http_response_code(404);

                            // If no items found
                            echo json_encode(
                                ['message' => 'Nothing to see here']
                            );
                            break;
                    }
                } else {
                    // Add error response code
                    http_response_code(404);

                    // If no items found
                    echo json_encode(
                        ['message' => 'Nothing to update here']
                    );
                }
            }
        }
        break;
    case 'DELETE':
        if ($_COOKIE['login']) {
            // If string two in url is not empty
            if (!empty($request[1])) {
                if (intval($id)) {
                    // Take string
                    switch ($request[1]) {
                        // If string = users
                        case 'users':
                            // Send id as parameter
                            $user->DELETE($id);
                            break;
                        // If string = studies
                        case 'studies':
                            // Send id as parameter
                            $studie->DELETE($id);
                            break;
                        // If string = work
                        case 'work':
                            // Send id as parameter
                            $work->DELETE($id);
                            break;
                        // If string = portfolio
                        case 'portfolio':
                            // Send id as parameter
                            $portfolio->DELETE($id);
                            break;
                        default:
                            // Add error response code
                            http_response_code(404);

                            // If no items found
                            echo json_encode(
                                ['message' => 'Nothing to see here']
                            );
                            break;
                    }
                } else {
                    // Add error response code
                    http_response_code(404);

                    // If no items found
                    echo json_encode(
                        ['message' => 'Nothing to delete here']
                    );
                }
            }
        }
        break;
}

if (isset($_SERVER['msg-error'])) {
    // Add error response code
    http_response_code(404);

    // If no items found
    echo json_encode(
        ['message' => $_SERVER['msg-error']]
    );
}
