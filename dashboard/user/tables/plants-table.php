<table class="table table-bordered table-hover">
<?php

require_once '../authentication/admin-class.php';

$user = new ADMIN();
if(!$user->isUserLoggedIn())
{
 $user->redirect('../../../private/admin/');
}

// Use the runQuery method to prepare and execute queries.
function get_total_row($user)
{
    $pdoQuery = "SELECT COUNT(*) as total_rows FROM plants WHERE status = :status";
    $pdoResult = $user->runQuery($pdoQuery);
    $pdoResult->execute([':status' => "available"]);
    $row = $pdoResult->fetch(PDO::FETCH_ASSOC);
    return $row['total_rows'];
}

$total_record = get_total_row($user);
$limit = '20';
$page = 1;
if(isset($_POST['page']))
{
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
}
else
{
    $start = 0;
}

$query = "SELECT * FROM plants WHERE status = :status";

$output = '';
if($_POST['query'] != '') {
    // Prepare the search term
    $search_term = $_POST['query'];
    $formatted_date = date("F j, Y", strtotime($search_term)); // Convert the search term to date format

    // Modify the query to search by email, activity, or formatted created_at date
    $query .= ' AND plant_name LIKE "%'.str_replace(' ', '%', $search_term).'%" 
                OR dry_threshold LIKE "%'.str_replace(' ', '%', $search_term).'%" 
                OR watered_threshold LIKE "%'.str_replace(' ', '%', $search_term).'%" ';
}

$query .= ' ORDER BY id DESC ';

$filter_query = $query . ' LIMIT '.$start.', '.$limit.'';

// Use the runQuery method to prepare and execute the query.
$statement = $user->runQuery($query);
$statement->execute([':status' => "available"]);
$total_data = $statement->rowCount();

// Use the runQuery method to prepare and execute the filtered query.
$statement = $user->runQuery($filter_query);
$statement->execute([':status' => "available"]);
$total_filter_data = $statement->rowCount();

if($total_data > 0)
{
    $output = '
        <div class="row-count">
            Showing ' . ($start + 1) . ' to ' . min($start + $limit, $total_data) . ' of ' . $total_record . ' entries
        </div>
        <thead>
            <th>#</th>
            <th>PLANT NAME</th>
            <th>WATERED THRESHOLD</th>
            <th>DRY THRESHOLD</th>
            <th>DATE ADDED</th>
            <th>ACTION</th>

        </thead>
    ';

    while($row = $statement->fetch(PDO::FETCH_ASSOC))
    {
        $output .= '
        <tr>
            <td>'.$row["id"].'</td>
            <td>'.$row["plant_name"].'</td>
            <td>'.$row["watered_threshold"].'</td>
            <td>'.$row["dry_threshold"].'</td>
            <td>'.date("F j, Y (h:i A)", strtotime($row['created_at'])).'</td>
            <td>
            <button type="button" class="btn btn-primary V"><a href="edit_plants?id='.$row["id"].'" class="edit"><i class="bx bxs-edit"></i></a></button>
            <button type="button" class="btn btn-danger V"><a href="controller/plants-controller?id='.$row["id"].'&delete_plants=1" class="delete"><i class="bx bxs-trash"></i></a></button>
            </td>  
            </tr>
        ';
    }
}
else
{
    echo '<h1>No data found</h1>';
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
