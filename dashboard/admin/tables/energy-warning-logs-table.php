<table class="table table-bordered table-hover">
    <?php

    require_once '../authentication/admin-class.php';

    $user = new ADMIN();
    if (!$user->isUserLoggedIn()) {
        $user->redirect('../../../private/admin/');
    }

    // 🔹 Fetch all rooms owned by this admin
    $stmt = $user->runQuery("SELECT id FROM rooms WHERE owner_id = :uid");
    $stmt->execute([":uid" => $_SESSION['adminSession']]);
    $rooms = $stmt->fetchAll(PDO::FETCH_COLUMN); // fetch only room_id values

    if (!$rooms) {
        echo "<h1 class='no_room'>No rooms found for this admin</h1>";
        exit;
    }

    // Convert array into a string for SQL IN clause
    $room_placeholders = implode(',', array_fill(0, count($rooms), '?'));

    // 🔹 Pagination setup
    $limit = 20;
    $page  = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
    $start = ($page - 1) * $limit;

    // 🔹 Base query
    $query = "SELECT * FROM energy_alerts WHERE room_id IN ($room_placeholders)";

    // 🔹 Search filter
    if (!empty($_POST['query'])) {
        $search_term = "%" . str_replace(' ', '%', $_POST['query']) . "%";
        $query .= " AND (submeter_id LIKE ? OR DATE_FORMAT(created_at, '%M %e, %Y') LIKE ?)";
        $params = array_merge($rooms, [$search_term, $search_term]);
    } else {
        $params = $rooms;
    }

    $query .= " ORDER BY id DESC LIMIT $start, $limit";

    // 🔹 Get total records
    $countQuery = "SELECT COUNT(*) as total FROM energy_alerts WHERE room_id IN ($room_placeholders)";
    $countStmt = $user->runQuery($countQuery);
    $countStmt->execute($rooms);
    $total_data = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // 🔹 Fetch paginated records
    $statement = $user->runQuery($query);
    $statement->execute($params);

    $output = '';
    if ($total_data > 0) {
        $output .= '
        <div class="row-count">
            Showing ' . ($start + 1) . ' to ' . min($start + $limit, $total_data) . ' of ' . $total_data . ' entries
        </div>
        <thead>
            <th>#</th>
            <th>ROOM NAME</th>
            <th>SUBMETER ID</th>
            <th>USER</th>
            <th>EMAIL</th>
            <th>ALERT DATE</th>
        </thead>
    ';

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

            $user_id = $row["user_id"];
            $pdoQuery = "SELECT * FROM users WHERE id = :id";
            $pdoResult = $user->runQuery($pdoQuery);
            $pdoResult->execute(array(":id" => $user_id));
            $user_data = $pdoResult->fetch(PDO::FETCH_ASSOC);

            $room_id = $row["room_id"];
            $pdoQuery = "SELECT * FROM rooms WHERE id = :id";
            $roomDetails = $user->runQuery($pdoQuery);
            $roomDetails->execute(array(":id" => $room_id));
            $room_data = $roomDetails->fetch(PDO::FETCH_ASSOC);

            $output .= '
        <tr>
            <td>' . $row["id"] . '</td>
            <td>' . $room_data["room_number"] . '</td>
            <td>' . $row["submeter_id"] . '</td>
            <td>' . $user_data["last_name"] . ', ' . $user_data["first_name"] . ' ' . $user_data["middle_name"] . '</td>            <td>' . $user_data["email"] . '</td>
            <td>' . date("F j, Y (h:i A)", strtotime($row['created_at'])) . '</td>
        </tr>';
        }
    } else {
        $output .= '<h1 class="no_room">No data found</h1>';
    }

    $output .= '</table>';
    $output .= '<div align="center"><ul class="pagination">';

    $total_links = ceil($total_data / $limit);
    $previous_link = '';
    $next_link = '';
    $page_link = '';

    if ($total_links > 5) {
        if ($page < 5) {
            for ($count = 1; $count <= 5; $count++) {
                $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
        } else {
            $end_limit = $total_links - 5;
            if ($page > $end_limit) {
                $page_array[] = 1;
                $page_array[] = '...';
                for ($count = $end_limit; $count <= $total_links; $count++) {
                    $page_array[] = $count;
                }
            } else {
                $page_array[] = 1;
                $page_array[] = '...';
                for ($count = $page - 1; $count <= $page + 1; $count++) {
                    $page_array[] = $count;
                }
                $page_array[] = '...';
                $page_array[] = $total_links;
            }
        }
    } else {
        $page_array[] = '...';
        for ($count = 1; $count <= $total_links; $count++) {
            $page_array[] = $count;
        }
    }

    for ($count = 0; $count < count($page_array); $count++) {
        if ($page == $page_array[$count]) {
            $page_link .= '
        <li class="page-item active">
            <a class="page-link" href="#">' . $page_array[$count] . ' <span class="sr-only"></span></a>
        </li>
        ';

            $previous_id = $page_array[$count] - 1;
            if ($previous_id > 0) {
                $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $previous_id . '">Previous</a></li>';
            } else {
                $previous_link = '
            <li class="page-item disabled">
                <a class="page-link" href="#">Previous</a>
            </li>
            ';
            }
            $next_id = $page_array[$count] + 1;
            if ($next_id > $total_links) {
                $next_link = '
            <li class="page-item disabled">
                <a class="page-link" href="#">Next</a>
            </li>
            ';
            } else {
                $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $next_id . '">Next</a></li>';
            }
        } else {
            if ($page_array[$count] == '...') {
                $page_link .= '
            <li class="page-item disabled">
                <a class="page-link" href="#">...</a>
            </li>
            ';
            } else {
                $page_link .= '
            <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $page_array[$count] . '">' . $page_array[$count] . '</a></li>
            ';
            }
        }
    }

    $output .= $previous_link . $page_link . $next_link;
    $output .= '</ul></div>';

    echo $output;

    ?>
    <script src="../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <script src="../../src/js/form.js"></script>
</table>