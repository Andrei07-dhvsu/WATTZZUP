<table class="table table-bordered table-hover">
<?php
require_once '../authentication/admin-class.php';

$user = new ADMIN();
if(!$user->isUserLoggedIn()){
    $user->redirect('../../../private/admin/');
}

$admin_id = $_SESSION['adminSession']; // logged-in admin id

// Get total row count (for pagination info only)
function get_total_row($user) {
    $pdoQuery = "SELECT COUNT(*) as total_rows FROM logs";
    $pdoResult = $user->runQuery($pdoQuery);
    $pdoResult->execute();
    $row = $pdoResult->fetch(PDO::FETCH_ASSOC);
    return $row['total_rows'];
}

$total_record = get_total_row($user);
$limit = 20;
$page  = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$start = ($page - 1) * $limit;

// 🔹 Build main query
// 1. logs of admin
// 2. logs of users linked via admin_access_key
$query = "
    SELECT logs.*, users.email 
    FROM logs 
    INNER JOIN users ON logs.user_id = users.id
    WHERE logs.user_id = :admin_id
       OR logs.user_id IN (
            SELECT u.id 
            FROM users u
            INNER JOIN admin_access_keys aak ON u.access_key = aak.access_key
            WHERE aak.admin_id = :admin_id
       )
";

// 🔎 Add search filter
if(!empty($_POST['query'])) {
    $search_term = $_POST['query'];
    $formatted_date = date("F j, Y", strtotime($search_term));

    $query .= ' AND (
        users.email LIKE :search 
        OR logs.activity LIKE :search 
        OR DATE_FORMAT(logs.created_at, "%M %e, %Y") LIKE :search
    )';
}

$query .= " ORDER BY logs.id DESC ";

// Pagination
$filter_query = $query . " LIMIT $start, $limit";

// Count total filtered
$statement = $user->runQuery($query);
$params = [":admin_id" => $admin_id];
if(!empty($_POST['query'])) {
    $params[":search"] = "%" . str_replace(' ', '%', $_POST['query']) . "%";
}
$statement->execute($params);
$total_data = $statement->rowCount();

// Get filtered data
$statement = $user->runQuery($filter_query);
$statement->execute($params);
$total_filter_data = $statement->rowCount();

$output = '';
if($total_data > 0){
    $output .= '
        <div class="row-count">
            Showing ' . ($start + 1) . ' to ' . min($start + $limit, $total_data) . ' of ' . $total_record . ' entries
        </div>
        <thead>
            <th>#</th>
            <th>USER</th>
            <th>ACTIVITY</th>
            <th>DATE ADDED</th>
        </thead>
    ';

    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $output .= '
        <tr>
            <td>'.$row["id"].'</td>
            <td>'.$row["email"].'</td>
            <td>'.$row["activity"].'</td>
            <td>'.date("F j, Y (h:i A)", strtotime($row['created_at'])).'</td>
        </tr>';
    }
} else {
    $output .= '<h1 class="no_room">No data found</h1>';
}

$output .= '</table>';
$output .= '<div align="center"><ul class="pagination">';

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

if($total_links > 5)
{
    if($page < 5)
    {
        for($count = 1; $count <= 5; $count++)
        {
            $page_array[] = $count;
        }
        $page_array[] = '...';
        $page_array[] = $total_links;
    }
    else
    {
        $end_limit = $total_links - 5;
        if($page > $end_limit)
        {
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $end_limit; $count <= $total_links; $count++)
            {
                $page_array[] = $count;
            }
        }
        else
        {
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $page - 1; $count <= $page + 1; $count++)
            {
                $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
        }
    }
}
else
{
    $page_array[] = '...';
    for($count = 1; $count <= $total_links; $count++)
    {
        $page_array[] = $count;
    }
}

for($count = 0; $count < count($page_array); $count++)
{
    if($page == $page_array[$count])
    {
        $page_link .= '
        <li class="page-item active">
            <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only"></span></a>
        </li>
        ';

        $previous_id = $page_array[$count] - 1;
        if($previous_id > 0)
        {
            $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
        }
        else
        {
            $previous_link = '
            <li class="page-item disabled">
                <a class="page-link" href="#">Previous</a>
            </li>
            ';
        }
        $next_id = $page_array[$count] + 1;
        if($next_id > $total_links)
        {
            $next_link = '
            <li class="page-item disabled">
                <a class="page-link" href="#">Next</a>
            </li>
            ';
        }
        else
        {
            $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
        }
    }
    else
    {
        if($page_array[$count] == '...')
        {
            $page_link .= '
            <li class="page-item disabled">
                <a class="page-link" href="#">...</a>
            </li>
            ';
        }
        else
        {
            $page_link .= '
            <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
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
