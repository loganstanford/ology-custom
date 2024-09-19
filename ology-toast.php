<?php
// Ensure this file is being included by a parent file
if (!defined('ABSPATH')) exit;

function ology_toast_create_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $item_details_table = $wpdb->prefix . 'ologytoast_item_details';

    $item_details_sql = "CREATE TABLE $item_details_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        location varchar(255) NOT NULL,
        order_id varchar(255) NOT NULL,
        order_number int(11) NOT NULL,
        sent_date datetime NOT NULL,
        order_date datetime NOT NULL,
        check_id varchar(255) NOT NULL,
        server varchar(255) NOT NULL,
        table_name varchar(255),
        dining_area varchar(255),
        service varchar(255),
        dining_option varchar(255),
        item_selection_id varchar(255) NOT NULL,
        item_id varchar(255),
        master_id varchar(255),
        sku varchar(255),
        plu varchar(255),
        menu_item varchar(255) NOT NULL,
        menu_subgroups varchar(255),
        menu_group varchar(255),
        menu varchar(255),
        sales_category varchar(255),
        gross_price decimal(10,2) NOT NULL,
        discount decimal(10,2),
        net_price decimal(10,2) NOT NULL,
        quantity decimal(10,2) NOT NULL,
        tax decimal(10,2) NOT NULL,
        void_status tinyint(1),
        deferred tinyint(1),
        tax_exempt tinyint(1),
        tax_inclusion_option varchar(255),
        dining_option_tax varchar(255),
        tab_name varchar(255),
        PRIMARY KEY  (id),
        UNIQUE KEY unique_order_item (order_id, item_selection_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($item_details_sql);

    // Check if table was created successfully
    if ($wpdb->get_var("SHOW TABLES LIKE '$item_details_table'") != $item_details_table) {
        error_log("Failed to create table: $item_details_table");
    }

    $item_modifiers_table = $wpdb->prefix . 'ologytoast_item_modifiers';

    $item_modifiers_sql = "CREATE TABLE $item_modifiers_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        location varchar(255) NOT NULL,
        order_id varchar(255) NOT NULL,
        order_number int(11) NOT NULL,
        sent_date datetime NOT NULL,
        order_date datetime NOT NULL,
        check_id varchar(255) NOT NULL,
        server varchar(255) NOT NULL,
        table_name varchar(255),
        dining_area varchar(255),
        service varchar(255),
        dining_option varchar(255),
        item_selection_id varchar(255) NOT NULL,
        modifier_id varchar(255) NOT NULL,
        master_id varchar(255),
        modifier_sku varchar(255),
        modifier_plu varchar(255),
        modifier_name varchar(255) NOT NULL,
        option_group_id varchar(255),
        option_group_name varchar(255),
        parent_menu_selection_item_id varchar(255),
        parent_menu_selection varchar(255),
        sales_category varchar(255),
        gross_price decimal(10,2) NOT NULL,
        discount decimal(10,2),
        net_price decimal(10,2) NOT NULL,
        quantity decimal(10,2) NOT NULL,
        void_status tinyint(1),
        void_reason_id varchar(255),
        void_reason varchar(255),
        PRIMARY KEY  (id),
        UNIQUE KEY unique_order_item_modifier (order_id, item_selection_id, modifier_id)
    ) $charset_collate;";

    dbDelta($item_modifiers_sql);

    // Check if table was created successfully
    if ($wpdb->get_var("SHOW TABLES LIKE '$item_modifiers_table'") != $item_modifiers_table) {
        error_log("Failed to create table: $item_modifiers_table");
    }
}

// Ensure this function runs
add_action('plugins_loaded', 'ology_toast_create_tables');

function ology_toast_admin_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
?>
<div class="wrap">
    <h1>Toast Integration</h1>
    <p><a href="<?php echo admin_url('admin.php?page=ology-toast-upload'); ?>" class="button button-primary">Upload
            Data</a></p>
    <?php echo do_shortcode('[ology_toast_beer_metrics]'); ?>
</div>
<?php
}

// Upload page for Toast data
function ology_toast_upload_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    if (isset($_POST['submit_csv'])) {
        ology_toast_process_uploaded_files();
    }
?>
<div class="wrap">
    <h1>Upload Toast CSV Files</h1>
    <form method="post" enctype="multipart/form-data">
        <table class="form-table">
            <tr>
                <th scope="row"><label for="csv_files">CSV Files</label></th>
                <td><input type="file" name="csv_files[]" id="csv_files" accept=".csv" multiple></td>
            </tr>
        </table>
        <?php submit_button('Upload and Process CSV Files', 'primary', 'submit_csv'); ?>
    </form>
</div>
<?php
}

function ology_toast_process_uploaded_files()
{
    if (empty($_FILES['csv_files']['name'][0])) {
        echo '<div class="error"><p>Please select at least one CSV file to upload.</p></div>';
        return;
    }

    $uploaded_files = $_FILES['csv_files'];
    $file_count = count($uploaded_files['name']);
    $success_count = 0;
    $error_count = 0;

    for ($i = 0; $i < $file_count; $i++) {
        if ($uploaded_files['error'][$i] === UPLOAD_ERR_OK) {
            $tmp_name = $uploaded_files['tmp_name'][$i];
            $name = $uploaded_files['name'][$i];

            // Read the first line of the CSV file to get the headers
            $handle = fopen($tmp_name, 'r');
            $headers = fgetcsv($handle);
            fclose($handle);

            // Determine the type of CSV based on the headers
            $file_type = determine_csv_type($headers);

            if ($file_type === 'item_details') {
                ology_toast_process_csv($tmp_name, 'item_details');
                $success_count++;
            } elseif ($file_type === 'item_modifiers') {
                ology_toast_process_csv($tmp_name, 'item_modifiers');
                $success_count++;
            } else {
                $error_count++;
                error_log("Unknown CSV type for file: $name");
            }
        } else {
            $error_count++;
            error_log("Error uploading file: " . $uploaded_files['name'][$i]);
        }
    }

    if ($success_count > 0) {
        echo '<div class="updated"><p>' . $success_count . ' CSV file(s) processed successfully.</p></div>';
    }
    if ($error_count > 0) {
        echo '<div class="error"><p>' . $error_count . ' CSV file(s) failed to process.</p></div>';
    }
}

function determine_csv_type($headers)
{
    // Convert headers to lowercase for case-insensitive comparison
    $headers = array_map('strtolower', $headers);

    // Check for unique headers in each file type
    if (in_array('modifier', $headers) && in_array('option group name', $headers)) {
        return 'item_modifiers';
    } elseif (in_array('menu item', $headers) && in_array('menu group', $headers)) {
        return 'item_details';
    }

    // If no match is found
    return 'unknown';
}

// Process CSV files
function ology_toast_process_csv($file, $type)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ologytoast_' . $type;

    if (($handle = fopen($file, "r")) !== FALSE) {
        $header = fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row = array_combine($header, $data);
            $process_function = "ology_toast_process_{$type}_row";
            if (function_exists($process_function)) {
                $processed_row = $process_function($row);
                $wpdb->insert($table_name, $processed_row);
            } else {
                error_log("Processing function not found for type: $type");
            }
        }
        fclose($handle);
    }
}

function ology_toast_process_item_details_row($row)
{
    return array(
        'location' => $row['Location'],
        'order_id' => $row['Order Id'],
        'order_number' => $row['Order #'],
        'sent_date' => date('Y-m-d H:i:s', strtotime($row['Sent Date'])),
        'order_date' => date('Y-m-d H:i:s', strtotime($row['Order Date'])),
        'check_id' => $row['Check Id'],
        'server' => $row['Server'],
        'table_name' => $row['Table'],
        'dining_area' => $row['Dining Area'],
        'service' => $row['Service'],
        'dining_option' => $row['Dining Option'],
        'item_selection_id' => $row['Item Selection Id'],
        'item_id' => $row['Item Id'],
        'master_id' => $row['Master Id'],
        'sku' => $row['SKU'],
        'plu' => $row['PLU'],
        'menu_item' => $row['Menu Item'],
        'menu_subgroups' => $row['Menu Subgroup(s)'],
        'menu_group' => $row['Menu Group'],
        'menu' => $row['Menu'],
        'sales_category' => $row['Sales Category'],
        'gross_price' => $row['Gross Price'],
        'discount' => $row['Discount'],
        'net_price' => $row['Net Price'],
        'quantity' => $row['Qty'],
        'tax' => $row['Tax'],
        'void_status' => $row['Void?'] === 'true' ? 1 : 0,
        'deferred' => $row['Deferred'] === 'true' ? 1 : 0,
        'tax_exempt' => $row['Tax Exempt'] === 'true' ? 1 : 0,
        'tax_inclusion_option' => $row['Tax Inclusion Option'],
        'dining_option_tax' => $row['Dining Option Tax'],
        'tab_name' => $row['Tab Name']
    );
}

function ology_toast_process_item_modifiers_row($row)
{
    return array(
        'location' => $row['Location'],
        'order_id' => $row['Order Id'],
        'order_number' => $row['Order #'],
        'sent_date' => date('Y-m-d H:i:s', strtotime($row['Sent Date'])),
        'order_date' => date('Y-m-d H:i:s', strtotime($row['Order Date'])),
        'check_id' => $row['Check Id'],
        'server' => $row['Server'],
        'table_name' => $row['Table'],
        'dining_area' => $row['Dining Area'],
        'service' => $row['Service'],
        'dining_option' => $row['Dining Option'],
        'item_selection_id' => $row['Item Selection Id'],
        'modifier_id' => $row['Modifier Id'],
        'master_id' => $row['Master Id'],
        'modifier_sku' => $row['Modifier SKU'],
        'modifier_plu' => $row['Modifier PLU'],
        'modifier_name' => $row['Modifier'],
        'option_group_id' => $row['Option Group ID'],
        'option_group_name' => $row['Option Group Name'],
        'parent_menu_selection_item_id' => $row['Parent Menu Selection Item ID'],
        'parent_menu_selection' => $row['Parent Menu Selection'],
        'sales_category' => $row['Sales Category'],
        'gross_price' => $row['Gross Price'],
        'discount' => $row['Discount'],
        'net_price' => $row['Net Price'],
        'quantity' => $row['Qty'],
        'void_status' => $row['Void?'] === 'true' ? 1 : 0,
        'void_reason_id' => $row['Void Reason ID'],
        'void_reason' => $row['Void Reason']
    );
}

function get_ology_toast_beer_metrics($start_date = null, $end_date = null, $limit = 100)
{
    global $wpdb;
    $item_details_table = $wpdb->prefix . 'ologytoast_item_details';
    $item_modifiers_table = $wpdb->prefix . 'ologytoast_item_modifiers';

    // Prepare the date range condition
    $date_condition = '';
    if ($start_date && $end_date) {
        $date_condition = $wpdb->prepare(
            " AND i.sent_date BETWEEN %s AND %s",
            $start_date,
            $end_date
        );
    }

    $query = $wpdb->prepare("
        SELECT 
            i.id, i.location, i.sent_date, i.menu_item, im.modifier_name,
            i.gross_price, i.net_price, i.quantity, i.discount, i.tax, 
            i.gross_price - i.discount + i.tax as total
        FROM 
            $item_details_table i
        LEFT JOIN 
            (SELECT 
                im2.*,
                ROW_NUMBER() OVER (PARTITION BY im2.order_id, im2.parent_menu_selection_item_id, im2.sent_date, im2.gross_price 
                                   ORDER BY im2.id) as rn
            FROM 
                $item_modifiers_table im2
            WHERE 
                im2.void_status = 0) im
        ON 
            i.order_id = im.order_id
            AND i.item_id = im.parent_menu_selection_item_id
            AND i.sent_date = im.sent_date
            AND i.gross_price = im.gross_price
            AND im.rn = 1
        WHERE 
            i.sales_category = 'Draft Beer'
            AND i.void_status = 0
            $date_condition
        ORDER BY i.sent_date DESC
        LIMIT %d
    ", $limit);

    $results = $wpdb->get_results($query, ARRAY_A);

    return $results;
}

add_shortcode('ology_toast_beer_metrics', 'ology_toast_beer_metrics_shortcode');

function ology_toast_beer_metrics_shortcode()
{
    wp_enqueue_style('jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_style('jquery-ui-timepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css');
    wp_enqueue_style('datatables', 'https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css');
    wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');

    wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-ui-timepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js', array('jquery', 'jquery-ui'), null, true);
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js', array('jquery'), null, true);
    wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), null, true);
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, true);

    global $wpdb;
    $item_details_table = $wpdb->prefix . 'ologytoast_item_details';
    $item_modifiers_table = $wpdb->prefix . 'ologytoast_item_modifiers';

    // Fetch unique values
    $locations = $wpdb->get_col("SELECT DISTINCT location FROM $item_details_table ORDER BY location");
    $menu_items = $wpdb->get_col("SELECT DISTINCT menu_item FROM $item_details_table WHERE sales_category = 'Draft Beer' ORDER BY menu_item");

    // Query to fetch and sort modifiers
    $modifiers = $wpdb->get_col("
    SELECT DISTINCT im.modifier_name 
    FROM $item_modifiers_table im
    JOIN $item_details_table id ON im.order_id = id.order_id AND im.parent_menu_selection_item_id = id.item_id
    WHERE id.sales_category = 'Draft Beer' AND im.void_status = 0
    ORDER BY 
        CASE 
            WHEN im.modifier_name REGEXP '^[0-9]+\\s*oz' THEN CAST(SUBSTRING_INDEX(im.modifier_name, ' ', 1) AS UNSIGNED)
            ELSE 9999  -- This will push non-numeric modifiers to the end
        END ASC,
        im.modifier_name ASC
    ");

    ob_start();
?>
<style>
/* Custom card style without max-width */
.ology-card {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    width: 100%;
    box-sizing: border-box;
}

#ology-toast-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-start;
    padding: 20px;
}

.date-time-group {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    width: 100%;
}

.date-group,
.time-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
    flex: 1;
    min-width: 200px;
}

.date-item,
.time-item {
    display: flex;
    gap: 10px;
}

.date-item>div,
.time-item>div {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.other-filter-group {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    width: 100%;
}

.other-filter-item {
    flex: 1;
    min-width: 200px;
}

.date-item label,
.time-item label,
.other-filter-item label {
    margin-bottom: 5px;
    min-width: 100px;
}

.date-item input,
.time-item input,
.other-filter-item select {
    width: 300px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Style adjustments for Select2 */
.select2-container {
    width: 100% !important;
}

.select2-selection .select2-selection--single {
    height: 48px;
    padding: 5px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 46px;
}

#apply_filters {
    margin-top: 20px;
    align-self: flex-end;
}

.ology-toast-filters-button {
    width: 100%;
    text-align: right;
}

/* Responsive adjustments */
@media (max-width: 768px) {

    .date-item,
    .time-item,
    .other-filter-item {
        flex: 1 1 100%;
    }
}

/* Flexbox for charts - 50% each */
.ology-toast-charts-container {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.ology-toast-chart-wrapper {
    flex: 0 1 calc(50% - 10px);
    padding: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 400px;
    /* Set a fixed height for consistency */
}

.ology-chart-title {
    font-size: 1.2em;
    margin-bottom: 10px;
    font-weight: bold;
    text-align: center;
}

/* Add this new style for the canvas */
.ology-toast-chart-wrapper canvas {
    width: 100% !important;
    height: auto !important;
    max-height: 350px;
    /* Adjust this value as needed */
}

/* Full width table */
#ology-toast-table-container {
    width: 100%;
}

/* Additional table styling */
#ology-toast-beer-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

#ology-toast-beer-table th,
#ology-toast-beer-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

#ology-toast-beer-table th {
    background-color: #f4f4f4;
}


#ology-toast-beer-table_length {
    margin-bottom: 10px;
}

#ology-toast-beer-table_length>label>select {
    margin-left: 10px;
    min-width: 60px;
}
</style>

<div id="ology-toast-filters" class="ology-card">
    <div class="date-group">
        <div class="date-item">
            <label for="start_date">Start Date:</label>
            <input type="text" id="start_date" name="start_date">
        </div>
        <div class="date-item">
            <label for="end_date">End Date:</label>
            <input type="text" id="end_date" name="end_date">
        </div>
    </div>

    <div class="time-group">
        <div class="time-item">
            <label for="start_time">Start Time:</label>
            <input type="text" id="start_time" name="start_time">
        </div>
        <div class="time-item">
            <label for="end_time">End Time:</label>
            <input type="text" id="end_time" name="end_time">
        </div>
    </div>

    <div class="other-filter-group">
        <div class="other-filter-item">
            <label for="location">Location:</label>
            <select id="location" name="location" class="select2" multiple>
                <option value="">All Locations</option>
                <?php foreach ($locations as $location): ?>
                <option value="<?php echo esc_attr($location); ?>"><?php echo esc_html($location); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="other-filter-item">
            <label for="menu_item">Menu Item:</label>
            <select id="menu_item" name="menu_item" class="select2" multiple>
                <option value="">All Menu Items</option>
                <?php foreach ($menu_items as $item): ?>
                <option value="<?php echo esc_attr($item); ?>"><?php echo esc_html($item); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="other-filter-item">
            <label for="modifier">Modifier:</label>
            <select id="modifier" name="modifier" class="select2" multiple>
                <option value="">All Modifiers</option>
                <?php foreach ($modifiers as $modifier): ?>
                <option value="<?php echo esc_attr($modifier); ?>"><?php echo esc_html($modifier); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="ology-toast-filters-button">
        <a href="#" id="apply_filters" class="button button-primary">Apply Filters</a>
    </div>
</div>

<div class="ology-toast-charts-container">
    <div class="ology-toast-chart-wrapper ology-card">
        <canvas id="modifierPieChart"></canvas>
    </div>
    <div class="ology-toast-chart-wrapper ology-card">

        <canvas id="hourlyBarChart"></canvas>
    </div>
</div>

<div id="ology-toast-table-container" class="ology-card">
    <table id="ology-toast-beer-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Location</th>
                <th>Sent Date</th>
                <th>Menu Item</th>
                <th>Modifier</th>
                <th>Gross Price</th>
                <th>Net Price</th>
                <th>Quantity</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
    </table>
</div>

<script>
jQuery(document).ready(function($) {

    // Initialize Select2 for dropdown filters
    $('#location, #menu_item, #modifier').select2({
        width: '100%',
        allowClear: true
    });

    $('#location').select2({
        placeholder: 'Select Taproom',
        allowClear: true
    });

    $('#menu_item').select2({
        placeholder: 'Select Beer',
        allowClear: true
    });

    $('#modifier').select2({
        placeholder: 'Select Size',
        allowClear: true
    });

    // Initialize datepicker for date inputs
    $('#start_date, #end_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    // Initialize timepicker for time inputs
    $('#start_time, #end_time').timepicker({
        timeFormat: 'HH:mm:ss'
    });

    let modifierChart, hourlyChart;

    function createCharts(modifierData, hourlyData) {
        const modifierCtx = document.getElementById('modifierPieChart').getContext('2d');
        const hourlyCtx = document.getElementById('hourlyBarChart').getContext('2d');

        // Prepare data for modifier pie chart
        const modifierLabels = Object.keys(modifierData);
        const modifierCounts = Object.values(modifierData);

        if (modifierChart) {
            modifierChart.data.labels = modifierLabels;
            modifierChart.data.datasets[0].data = modifierCounts;
            modifierChart.update();
        } else {
            modifierChart = new Chart(modifierCtx, {
                type: 'pie',
                data: {
                    labels: modifierLabels,
                    datasets: [{
                        data: modifierCounts,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'Beer Sales by Size'
                        }
                    }
                }
            });
        }

        if (hourlyChart) {
            hourlyChart.data.labels = hourlyData.map(item => item.hour);
            hourlyChart.data.datasets[0].data = hourlyData.map(item => item.count);
            hourlyChart.update();
        } else {
            hourlyChart = new Chart(hourlyCtx, {
                type: 'bar',
                data: {
                    labels: hourlyData.map(item => item.hour),
                    datasets: [{
                        label: 'Orders per Hour',
                        data: hourlyData.map(item => item.count),
                        backgroundColor: '#36A2EB'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Beer Sales by Hour'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Orders'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Hour of Day'
                            }
                        }
                    }
                }
            });
        }
    }

    function formatCurrency(data, type, row) {
        if (type === 'display') {
            return '$' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
        return data;
    }

    var table = $('#ology-toast-beer-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: function(d) {
                d.action = 'get_ology_toast_beer_data';
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.start_time = $('#start_time').val();
                d.end_time = $('#end_time').val();
                d.location = $('#location').val();
                d.menu_item = $('#menu_item').val();
                d.modifier = $('#modifier').val();
            },
            dataSrc: function(json) {
                createCharts(json.chartData.modifierData, json.chartData.hourlyData);
                return json.data;
            }
        },
        columns: [{
                data: 'id'
            },
            {
                data: 'location'
            },
            {
                data: 'sent_date'
            },
            {
                data: 'menu_item'
            },
            {
                data: 'modifier_name'
            },
            {
                data: 'gross_price',
                render: formatCurrency
            },
            {
                data: 'net_price',
                render: formatCurrency
            },
            {
                data: 'quantity'
            },
            {
                data: 'discount',
                render: formatCurrency
            },
            {
                data: 'tax',
                render: formatCurrency
            },
            {
                data: 'total',
                render: formatCurrency
            }
        ],
        order: [
            [2, 'desc'] // Sort by sent_date descending by default
        ],
        pageLength: 25,
        searching: false,
        autoWidth: false

    });

    // Update the filter application code
    $('#apply_filters').on('click', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
});
</script>
<?php
    return ob_get_clean();
}

// AJAX handler for beer metrics datatable
add_action('wp_ajax_get_ology_toast_beer_data', 'get_ology_toast_beer_data');
add_action('wp_ajax_nopriv_get_ology_toast_beer_data', 'get_ology_toast_beer_data');

function get_ology_toast_beer_data()
{
    global $wpdb;
    $item_details_table = $wpdb->prefix . 'ologytoast_item_details';
    $item_modifiers_table = $wpdb->prefix . 'ologytoast_item_modifiers';

    $columns = array(
        0 => 'id',
        1 => 'location',
        2 => 'sent_date',
        3 => 'menu_item',
        4 => 'modifier_name',
        5 => 'gross_price',
        6 => 'net_price',
        7 => 'quantity',
        8 => 'discount',
        9 => 'tax',
        10 => 'total'
    );

    $limit = $_POST['length'];
    $start = $_POST['start'];
    $order = $columns[$_POST['order'][0]['column']];
    $dir = $_POST['order'][0]['dir'];

    $query = "
        SELECT 
            i.id, i.location, i.sent_date, i.menu_item, im.modifier_name,
            i.gross_price, i.net_price, i.quantity, i.discount, i.tax, 
            i.gross_price - i.discount + i.tax as total
        FROM 
            $item_details_table i
        LEFT JOIN 
            (SELECT 
                im2.*,
                ROW_NUMBER() OVER (PARTITION BY im2.order_id, im2.parent_menu_selection_item_id, im2.sent_date, im2.gross_price 
                                   ORDER BY im2.id) as rn
            FROM 
                $item_modifiers_table im2
            WHERE 
                im2.void_status = 0) im
        ON 
            i.order_id = im.order_id
            AND i.item_id = im.parent_menu_selection_item_id
            AND i.sent_date = im.sent_date
            AND i.gross_price = im.gross_price
            AND im.rn = 1
        WHERE 
            i.sales_category = 'Draft Beer'
            AND i.void_status = 0
    ";

    $where_clauses = array();

    // Handle date range
    if (!empty($_POST['start_date']) && !empty($_POST['end_date'])) {
        $where_clauses[] = $wpdb->prepare(
            "DATE(i.sent_date) BETWEEN %s AND %s",
            $_POST['start_date'],
            $_POST['end_date']
        );
    }

    // Handle time range
    if (!empty($_POST['start_time']) && !empty($_POST['end_time'])) {
        $where_clauses[] = $wpdb->prepare(
            "TIME(i.sent_date) BETWEEN %s AND %s",
            $_POST['start_time'],
            $_POST['end_time']
        );
    }

    // Handle location filter
    if (!empty($_POST['location'])) {
        $locations = is_array($_POST['location']) ? $_POST['location'] : [$_POST['location']];
        $placeholders = implode(',', array_fill(0, count($locations), '%s'));
        $where_clauses[] = $wpdb->prepare("i.location IN ($placeholders)", $locations);
    }

    // Handle menu item filter
    if (!empty($_POST['menu_item'])) {
        $menu_items = is_array($_POST['menu_item']) ? $_POST['menu_item'] : [$_POST['menu_item']];
        $placeholders = implode(',', array_fill(0, count($menu_items), '%s'));
        $where_clauses[] = $wpdb->prepare("i.menu_item IN ($placeholders)", $menu_items);
    }

    // Handle modifier filter
    if (!empty($_POST['modifier'])) {
        $modifiers = is_array($_POST['modifier']) ? $_POST['modifier'] : [$_POST['modifier']];
        $placeholders = implode(',', array_fill(0, count($modifiers), '%s'));
        $where_clauses[] = $wpdb->prepare("im.modifier_name IN ($placeholders)", $modifiers);
    }

    if (!empty($where_clauses)) {
        $query .= " AND " . implode(" AND ", $where_clauses);
    }

    $total_query = "SELECT COUNT(1) FROM ($query) AS combined_table";
    $total = $wpdb->get_var($total_query);

    $data_query = $query . " ORDER BY $order $dir LIMIT $start, $limit";
    $result = $wpdb->get_results($data_query, ARRAY_A);

    // Get all data for charts
    $all_data_query = $query . " ORDER BY i.sent_date";
    $all_data = $wpdb->get_results($all_data_query, ARRAY_A);

    $chart_data = process_chart_data($all_data);

    $data = array(
        "draw" => intval($_POST['draw']),
        "recordsTotal" => intval($total),
        "recordsFiltered" => intval($total),
        "data" => $result,
        "hasData" => !empty($result),
        "chartData" => $chart_data
    );

    wp_send_json($data);
}

function process_chart_data($all_data)
{
    $modifier_data = array();
    $hourly_data = array_fill(7, 17, 0); // Initialize hourly data from 7am to 11pm

    foreach ($all_data as $row) {
        // Process modifier data
        $modifier = $row['modifier_name'];
        if (!isset($modifier_data[$modifier])) {
            $modifier_data[$modifier] = 0;
        }
        $modifier_data[$modifier]++;

        // Process hourly data
        $hour = intval(date('G', strtotime($row['sent_date'])));
        if ($hour >= 7 && $hour <= 23) {
            $hourly_data[$hour]++;
        }
    }

    // Sort modifier data and get top 10
    arsort($modifier_data);
    $modifier_data = array_slice($modifier_data, 0, 10, true);

    // Format hourly data
    $formatted_hourly_data = [];
    foreach ($hourly_data as $hour => $count) {
        $formatted_hour = $hour <= 12 ? $hour . 'am' : ($hour - 12) . 'pm';
        if ($hour == 12) $formatted_hour = '12pm';
        if ($hour == 23) $formatted_hour = '11pm';
        $formatted_hourly_data[] = [
            'hour' => $formatted_hour,
            'count' => $count
        ];
    }

    return [
        'modifierData' => $modifier_data,
        'hourlyData' => $formatted_hourly_data
    ];
}