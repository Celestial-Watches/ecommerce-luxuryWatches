<?php
// filterModule.php
/**
 * Retrieve distinct values from a given table and column.
 *
 * @param mysqli $conn Database connection.
 * @param string $table Table name.
 * @param string $column Column name.
 * @return array List of distinct values.
 */
function getDistinctValues($conn, $table, $column)
{
    $sql = "SELECT DISTINCT $column FROM $table WHERE $column <> '' ORDER BY $column ASC";
    $result = $conn->query($sql);
    $values = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $values[] = $row[$column];
        }
    }
    return $values;
}

/**
 * Render the dynamic filter modal.
 *
 * This function outputs a modal with a left sidebar for filter categories and a right panel that shows options.
 * The function uses advanced PHP techniques (output buffering) so that the HTML can be reused.
 *
 * @param mysqli $conn Database connection.
 * @return string HTML content for the filter modal.
 */
function renderFilterModal($conn)
{
    // Define filter configuration:
    // key => [Display Name, Source Table, Column Name, Layout Class for checkboxes]
    // For "price", we use a custom layout.
    $filters = [
        'brand'             => ['Brand', 'product_details', 'brand', ''],
        'model'             => ['Model', 'product_details', 'model', 'two-columns'],
        'price'             => ['Price', 'products', 'price', ''], // custom layout
        'gender'            => ['Gender', 'product_details', 'gender', 'two-columns'],
        'bracelet_color'    => ['Bracelet Color', 'product_details', 'bracelet_color', 'three-columns'],
        'case_material'     => ['Case Material', 'product_details', 'case_material', 'three-columns'],
        'bracelet_material' => ['Bracelet Material', 'product_details', 'bracelet_material', 'three-columns'],
        'case_diameter'     => ['Case Diameter', 'product_details', 'case_diameter', 'three-columns'],
        'bezel_material'    => ['Bezel Material', 'product_details', 'bezel_material', 'three-columns'],
        'movement'          => ['Movement', 'product_details', 'movement', 'three-columns'],
        'power_reserve'     => ['Power Reserve', 'product_details', 'power_reserve', 'three-columns'],
        'glass'             => ['Glass', 'product_details', 'glass', 'three-columns'],
        'clasp_type'        => ['Clasp Type', 'product_details', 'clasp_type', 'three-columns'],
        'water_resistance'  => ['Water Resistance', 'product_details', 'water_resistance', 'three-columns'],
        'dial_numerals'     => ['Dial Numerals', 'product_details', 'dial_numerals', 'three-columns'],
        'clasp_material'    => ['Clasp Material', 'product_details', 'clasp_material', 'three-columns'],
        'year'              => ['Year', 'products', 'year', 'three-columns']
    ];

    // 'status'            => ['Status', 'products', 'status', 'two-columns'],
    // 'limited_edition'   => ['Limited Edition', 'products', 'limited_edition', ''], // single checkbox
    //     'papers'            => ['Papers', 'products', 'papers', ''], // single checkbox
    //     'box'               => ['Box', 'products', 'box', ''], // single checkbox
    //     'condition'         => ['Condition', 'products', 'condition', 'three-columns'],


    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            /* Button to open the modal */
            .open-filter-btn {
                margin: 20px;
                padding: 12px 20px;
                background: #212121;
                color: #fff;
                border: none;
                cursor: pointer;
                text-transform: uppercase;
                font-weight: bold;
            }

            /* Modal Overlay */
            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.4);
                display: none;
                z-index: 9998;
            }

            .modal-overlay.active {
                display: block;
            }

            /* Filter Modal Container */
            .filter-modal {
                position: fixed;
                top: 50%;
                left: 50%;
                width: 90%;
                max-width: 1024px;
                /* max-height: 90vh; */
                background: #fff;
                transform: translate(-50%, -50%);
                border-radius: 4px;
                display: none;
                z-index: 9999;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
                overflow: hidden;
            }

            .filter-modal.active {
                display: block;
            }

            .alphabet-flex {
                display: flex;
                gap: 10px;
            }

            .alphabet-group {
                padding: 10px 0px;
            }

            .alphaGroup-head {
                display: flex;
                gap: 10px;
                align-items: center;
            }

            .alphaGroup-head span {
                border-bottom: 1px solid #E9E9E9;
                width: 100%;
            }

            .alphaGroup-head strong {
                font-style: normal;
                font-weight: 700;
                font-size: 21px;
                line-height: 25px;
                text-transform: uppercase;
                color: #000000;
                margin-bottom: 10px;
                position: relative;
            }

            /* Modal Header */
            .filter-modal-header {
                background: #fff;
                padding: 16px;
                border-bottom: 1px solid #ddd;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .filter-modal-title {
                height: 95px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-weight: 600;
                text-transform: uppercase;
                padding: 28px 30px;
            }

            .filter-modal-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                line-height: 1;
            }

            /* Modal Body: Two Columns */
            .filter-modal-body {
                display: flex;
                height: calc(90vh - 120px);
            }

            /* Left Sidebar: Categories List */
            .filter-categories {
                width: 25%;
                border-right: 1px solid #eee;
                overflow-y: auto;
                background: #fafafa;
            }

            .filter-categories ul {
                list-style: none;
            }

            .filter-categories li {
                padding: 14px 30px;
                border-bottom: 1px solid #eee;
                text-transform: uppercase;
                font-style: normal;
                font-weight: 700;
                font-size: 14px;
                line-height: 21px;
                color: #000000;
                cursor: pointer;
            }

            .filter-categories li.active,
            .filter-categories li:hover {
                background: #F3F4F4;
                color: #000;
            }

            /* Right Panel: Dynamic Filter Options */
            .filter-options-panel {
                padding: 40px 34px 50px 54px;
                height: 100%;
                width: 75%;
                overflow-y: scroll;
                position: relative;
            }

            .filter-section {
                margin-bottom: 20px;
            }

            .filter-section h2 {
                font-size: 0.9rem;
                font-weight: bold;
                text-transform: uppercase;
                margin-bottom: 12px;
            }

            /* Search Bar (for Brand, Model) */
            .filter-search {
                display: flex;
                gap: 8px;
                margin-bottom: 12px;
            }

            .filter-search input {
                flex: 1;
                padding: 6px;
                font-size: 0.85rem;
                border-bottom: 1px solid #ccc !important;
                border: none;
                margin-bottom: 10px;
            }

            .filter-search button {
                padding: 6px 12px;
                font-size: 0.85rem;
                cursor: pointer;
                border: none;
                background: #f5f5f5;
            }

            /* Checkbox List */
            .checkbox-list label {
                display: flex;
                font-size: 0.85rem;
                margin-bottom: 6px;
                cursor: pointer;
                align-items: center;
            }

            .checkbox-list input[type="checkbox"] {
                margin-right: 6px;
                width: auto;
                color: #E9E9E9;
                accent-color: black;
            }

            /* Layout: 2 Columns */
            .two-columns {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            /* Layout: 3 Columns */
            .three-columns {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 8px;
            }

            /* Price Range Section */
            .price-range {
                margin-bottom: 12px;
            }

            .price-inputs {
                display: flex;
                gap: 8px;
            }

            .price-inputs label {
                flex: 1;
                display: flex;
                flex-direction: column;
                font-size: 0.8rem;
            }

            .price-inputs input {
                margin-top: 4px;
                padding: 4px;
                border: 1px solid #ccc;
            }

            /* Modal Bottom: Common Buttons */
            .filter-modal-footer {
                padding: 16px;
                border-top: 1px solid #ddd;
                background: #fff;
                display: flex;
                justify-content: space-between;
            }

            .filter-modal-footer button {
                padding: 10px 16px;
                font-size: 0.85rem;
                cursor: pointer;
                font-weight: 600;
                text-transform: uppercase;
            }

            .remove-filters {
                font-style: normal;
                font-weight: 700;
                font-size: 14px;
                line-height: 17px;
                text-transform: uppercase;
                color: #000000;
                cursor: pointer;
                border: none;
            }

            .display-products {
                background: rgb(17, 17, 17);
                border: 0;
                border-style: none;
                color: #fff;
                outline: 0;
                cursor: pointer;
                -webkit-transition: all .4s;
                -moz-transition: all .4s;
                -o-transition: all .4s;
                transition: all .4s;
            }
        </style>
    </head>

    <body>


        <!-- Filter Modal Markup -->
        <div class="modal-overlay" id="filterModalOverlay"></div>
        <div class="filter-modal" id="filterModal">
            <div class="filter-modal-header">
                <span class="filter-modal-title">Filter</span>
                <button class="filter-modal-close" onclick="closeFilterModal()">&times;</button>
            </div>
            <div class="filter-modal-body">
                <!-- Left Sidebar: Filter Categories -->
                <div class="filter-categories">
                    <ul>
                        <?php foreach ($filters as $key => $data): ?>
                            <li data-category="<?php echo $key; ?>" onclick="changeFilterCategory(this)" <?php echo ($key == 'brand') ? 'class="active"' : ''; ?>>
                                <?php echo $data[0]; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!-- Right Panel: Filter Options -->
                <div class="filter-options-panel" id="filterOptionsPanel">
                    <?php
                    foreach ($filters as $key => $data):
                        $display = $data[0];
                        $table   = $data[1];
                        $column  = $data[2];
                        $layout  = $data[3];
                    ?>
                        <?php if ($key == 'price'): ?>
                            <div id="filter-<?php echo $key; ?>" class="filter-section" style="display: none;">
                                <h2><?php echo $display; ?></h2>
                                <div class="price-range">
                                    <!-- Insert a double-range slider here if desired -->
                                    <input type="range" min="0" max="500000" value="0">
                                </div>
                                <div class="price-inputs">
                                    <label>Min Price
                                        <input type="number" value="0">
                                    </label>
                                    <label>Max Price
                                        <input type="number" value="500000">
                                    </label>
                                </div>
                            </div>
                        <?php elseif (in_array($key, ['limited_edition', 'papers', 'box'])): ?>
                            <!-- Single Checkbox Filters -->
                            <div id="filter-<?php echo $key; ?>" class="filter-section" style="display: none;">
                                <h2><?php echo $display; ?></h2>
                                <div class="checkbox-list">
                                    <label>
                                        <input type="checkbox" name="<?php echo $key; ?>" value="1">
                                        <?php echo $display; ?>
                                    </label>
                                </div>
                            </div>
                        <?php else:
                            $values = getDistinctValues($conn, $table, $column);
                        ?>
                            <div id="filter-<?php echo $key; ?>" class="filter-section" style="display: <?php echo ($key == 'brand') ? 'block' : 'none'; ?>;">
                                <h2><?php echo $display; ?></h2>
                                <?php if (in_array($key, ['brand', 'model'])): ?>
                                    <div class="filter-search">
                                        <input type="text" placeholder="Search <?php echo strtolower($display); ?>...">
                                        <button>Search</button>
                                    </div>
                                <?php endif; ?>
                                <div class="checkbox-list <?php echo $layout; ?>">
                                    <?php
                                    if ($key == 'brand'):
                                        // Group brand values alphabetically
                                        $grouped = [];
                                        foreach ($values as $v) {
                                            $letter = strtoupper(substr($v, 0, 1));
                                            $grouped[$letter][] = $v;
                                        }
                                        ksort($grouped);
                                        foreach ($grouped as $letter => $group):
                                    ?>
                                            <div class="alphabet-group">
                                                <div class="alphaGroup-head">
                                                    <strong><?php echo $letter; ?></strong>
                                                    <span></span>
                                                </div>
                                                <div class="alphabet-flex">
                                                    <?php foreach ($group as $v): ?>
                                                        <label>
                                                            <input type="checkbox" name="<?php echo $key; ?>[]" value="<?php echo htmlspecialchars($v); ?>">
                                                            <?php echo htmlspecialchars($v); ?>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php
                                        endforeach;
                                    else:
                                        foreach ($values as $v):
                                        ?>
                                            <label>
                                                <input type="checkbox" name="<?php echo $key; ?>[]" value="<?php echo htmlspecialchars($v); ?>">
                                                <?php echo htmlspecialchars($v); ?>
                                            </label>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </div>
                    <?php endif;
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="filter-modal-footer">
                <button class="remove-filters" onclick="removeAllFilters()">Remove All Filters</button>
                <button class="display-products" onclick="applyFilters()">Display Products</button>
            </div>
        </div>

    </body>

    </html>
<?php
    return ob_get_clean();
}
?>